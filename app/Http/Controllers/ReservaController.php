<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ReservaComprobanteMail;
use Illuminate\Support\Facades\Mail;

class ReservaController extends Controller
{
    public function index(){    
        $locales = DB::select("SELECT
                                L.ID_Local,
                                L.nombre,
                                L.direccion,
                                L.latitud,
                                L.longitud,
                                M.URL,
                                U.telefono,
                                CONCAT(U.nombre,' ',U.primerApellido, ' ', U.segundoApellido) AS nombreCompleto
                            FROM
                                locales L
                            LEFT JOIN
                                multimedia M ON M.ID_Local = L.ID_Local
                                AND M.ID_Multimedia = (
                                    SELECT MAX(M2.ID_Multimedia)
                                    FROM multimedia M2
                                    WHERE M2.ID_Local = L.ID_Local
                                )
                            INNER JOIN users U ON L.ID_Local = U.local
                            WHERE
                                L.estado = 1;");
        foreach ($locales as $local) {
            $local->imagenes = DB::select("SELECT M.URL
                                        FROM multimedia M
                                        WHERE M.ID_Local = ?",
                                        [$local->ID_Local]);
        // Obtener las canchas asociadas a este local
        $local->canchas = DB::select("SELECT ID_Cancha, nombre, precio
                                      FROM canchas
                                      WHERE ID_Local = ? AND estado=1 AND estado_cancha LIKE 'DISPONIBLE'", [$local->ID_Local]);
        }
    // Retorna la vista con los locales y sus canchas
        return view('welcome', ['locales' => $locales]);
    }
    public function reserva(Request $request){
        if (Auth::check()) {
            $idusuario=auth()->user()->id;
            $reservas = $request->input('reservas', []);
            DB::beginTransaction();
            try {
                foreach ($reservas as $reserva) {
                    // Verificar si ya existe una reserva en la misma cancha, fecha y rango de horas
                    $existeReserva = DB::table('reservas')
                        ->join('detalle_reserva', 'reservas.ID_Reserva', '=', 'detalle_reserva.ID_Reserva')
                        ->where('detalle_reserva.ID_Cancha', $reserva['canchas'])
                        ->where('reservas.Fecha_Reserva', $reserva['fecha'])
                        ->where(function($query) use ($reserva) {
                            $query->whereBetween('reservas.Hora_Inicio', [$reserva['horaInicio'], $reserva['horaFin']])
                                  ->orWhereBetween('reservas.Hora_Fin', [$reserva['horaInicio'], $reserva['horaFin']])
                                  ->orWhere(function($query) use ($reserva) {
                                      $query->where('reservas.Hora_Inicio', '<=', $reserva['horaInicio'])
                                            ->where('reservas.Hora_Fin', '>=', $reserva['horaFin']);
                                  });
                        })
                        ->exists();
    
                    if ($existeReserva) {
                        return back()->with('error', 'Ya existe una reserva para esta cancha en el rango de horas seleccionado.');
                    }
    
                    // Insertar la reserva si no hay conflictos
                    $reservaId = DB::table('reservas')->insertGetId([
                        'Fecha_Reserva' => $reserva['fecha'],
                        'Hora_Inicio' => $reserva['horaInicio'],
                        'Hora_Fin' => $reserva['horaFin'],
                        'id' => $idusuario
                    ]);
    
                    // Relacionar cancha con reserva
                    DB::table('detalle_reserva')->insert([
                        'ID_Cancha' => $reserva['canchas'],
                        'ID_Reserva' => $reservaId,
                    ]);
                }
    
                DB::commit();
                return back()->with('success', 'Reserva realizada con exito');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error al agregar las canchas: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('login');
        }
    }
    public function Reservas(){
        $idlocal = auth()->user()->local;
    $idUsuario = auth()->user()->id;

    // Si el usuario tiene un local asignado
    if (!empty($idlocal)) {
        $sql = DB::select("SELECT u.id,r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva,u.nombre AS usuario_nombre,
                            u.email AS usuario_email, u.primerApellido, u.segundoApellido,
                            TIMESTAMPDIFF(DAY, NOW(), CONCAT(r.Fecha_Reserva, ' ', r.Hora_Inicio)) AS dias_restantes,
                            TIMESTAMPDIFF(HOUR, NOW(), CONCAT(r.Fecha_Reserva, ' ', r.Hora_Inicio)) % 24 AS horas_restantes
                            FROM locales l
                            JOIN canchas c ON l.ID_Local = c.ID_Local
                            JOIN detalle_reserva dr ON c.ID_Cancha = dr.ID_Cancha
                            JOIN reservas r ON dr.ID_Reserva = r.ID_Reserva
                            JOIN users u ON r.id = u.id 
                            WHERE l.ID_Local = ?", [$idlocal]);

    // Si el usuario NO tiene un local asignado
    } else {
        $sql = DB::select("SELECT L.nombre,r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva,
                            L.latitud,L.longitud,
                            TIMESTAMPDIFF(DAY, NOW(), CONCAT(r.Fecha_Reserva, ' ', r.Hora_Inicio)) AS dias_restantes,
                            TIMESTAMPDIFF(HOUR, NOW(), CONCAT(r.Fecha_Reserva, ' ', r.Hora_Inicio)) % 24 AS horas_restantes,
                            r.fecha_creacion
                            FROM reservas r
                            INNER JOIN detalle_reserva D ON D.ID_Reserva=r.ID_Reserva
                            INNER JOIN canchas C ON C.ID_Cancha=D.ID_Cancha
                            INNER JOIN locales L ON L.ID_Local=C.ID_Local 
                            WHERE r.id = ?", [$idUsuario]);
    }
        return view('.pages.reservas', ['sql' => $sql]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'Estado_Reserva' => 'required|in:0,1,2', // Cambia a números si es necesario
        ]);

        DB::table('reservas')
            ->where('ID_Reserva', $id)
            ->update(['Estado_Reserva' => $request->Estado_Reserva]);

        return redirect()->back()->with('success', 'Estado de la reserva actualizado correctamente.');
    }
    public function cancelar($id)
    {
            $sql = DB::update("UPDATE reservas SET estado_reserva = ? WHERE ID_Reserva = ?", [
                'Cancelada', // Cambia el estado a 'Cancelado'
                $id // ID de la reserva pasada desde la solicitud
            ]);

            return redirect()->back()->with('success', 'Reserva cancelada exitosamente.');
    }
    public function getReserva(Request $request)
    {
        $usuarioId = auth()->user()->id;

    // Validar que la fecha de creación se envíe
    $request->validate([
        'fecha_creacion' => 'required|date',
    ]);

    $fechaCreacion = $request->fecha_creacion;

    $reservas = DB::select("SELECT r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva,
                        L.nombre AS nombre_local, CONCAT(u.nombre,' ',u.primerApellido,' ',u.segundoApellido) AS nombre_cliente, 
                        u.email AS email_cliente, r.fecha_creacion, c.precio,
                        TIMESTAMPDIFF(MINUTE, r.Hora_Inicio, r.Hora_Fin) / 60 * c.precio AS total_por_reserva
                    FROM reservas r
                    INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                    INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                    INNER JOIN locales L ON c.ID_Local = L.ID_Local
                    INNER JOIN users u ON r.id = u.id
                    WHERE u.id = ? -- Usa el ID del usuario autenticado
                    AND r.estado_reserva = 1
                    AND DATE(r.Fecha_Reserva) = DATE(?)
                    ORDER BY r.Fecha_Reserva DESC;", [$usuarioId, $fechaCreacion]);


    $pdf = Pdf::loadView('.pages.reportes.cotizacion', compact('reservas'));
    return $pdf->stream();
    }
    public function reservaComprobante(Request $request)
    {
        $id=$request->id;
        $fecha=$request->fechReserva;
        $reservas = DB::select("SELECT r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva,
                    L.nombre AS nombre_local, 
                    CONCAT(u.nombre,' ',u.primerApellido,' ',u.segundoApellido) AS nombreCompleto, 
                    u.email AS email_cliente,
                    r.fecha_creacion, c.precio,
                    TIMESTAMPDIFF(MINUTE, r.Hora_Inicio, r.Hora_Fin) / 60 * c.precio AS total_por_reserva,
                    P.descuento, P.Fecha_Inicio, P.Fecha_Fin
                FROM reservas r
                INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                INNER JOIN locales L ON c.ID_Local = L.ID_Local
                LEFT JOIN precios P ON P.ID_Local = L.ID_Local 
                    AND CURDATE() BETWEEN P.Fecha_Inicio AND P.Fecha_Fin 
                INNER JOIN users u ON r.id = u.id
                WHERE r.id = ?
                AND r.estado_reserva = 1
                AND DATE(r.Fecha_Reserva) = ?;", [$id, $fecha]);
        $pdf = Pdf::loadView('.pages.reportes.comprobante', compact('reservas'));
        return $pdf->stream();
    }
    public function enviarComprobante(Request $request)
    {
        $id = $request->id;
        $fecha = $request->fechaRserva; // Cambié el nombre de la variable a fechaRserva
        $email = $request->email; // Asegúrate de que el nombre sea 'email'
        
        $reservas = DB::select("SELECT r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva,
                    L.nombre AS nombre_local, 
                    CONCAT(u.nombre,' ',u.primerApellido,' ',u.segundoApellido) AS nombreCompleto, 
                    u.email AS email_cliente,
                    r.fecha_creacion, c.precio,
                    TIMESTAMPDIFF(MINUTE, r.Hora_Inicio, r.Hora_Fin) / 60 * c.precio AS total_por_reserva,
                    P.descuento, P.Fecha_Inicio, P.Fecha_Fin
                FROM reservas r
                INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                INNER JOIN locales L ON c.ID_Local = L.ID_Local
                LEFT JOIN precios P ON P.ID_Local = L.ID_Local 
                    AND CURDATE() BETWEEN P.Fecha_Inicio AND P.Fecha_Fin 
                INNER JOIN users u ON r.id = u.id
                WHERE r.id = ?
                AND r.estado_reserva = 1
                AND DATE(r.Fecha_Reserva) = ?;", [$id, $fecha]);

        $pdf = Pdf::loadView('.pages.reportes.comprobante', compact('reservas'));
        $pdfContent = $pdf->output();

        Mail::to($email)->send(new ReservaComprobanteMail($reservas, $pdfContent));

        return back()->with('success', 'Comprobante enviado exitosamente.');
    }
    public function estadoPagado(Request $request){
        try {
            $sql=DB::insert("UPDATE reservas SET Estado_Reserva=4 WHERE ID_Reserva=?",[
                $request->id
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql==true)
        {
            return back()->with("correcto","usuarios eliminado correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al eliminar un usuario");
        }
    }
    public function reporteIngresos(Request $request){
       $reservas = DB::select("SELECT
    (c.precio * (1 - COALESCE(p.descuento, 0) / 100)) AS Precio_Final, 
    r.ID_Reserva,
    r.Fecha_Reserva,
    r.Hora_Inicio,
    r.Hora_Fin,
    c.nombre AS Nombre_Cancha,
    l.direccion AS Direccion_Local,
    l.nombre AS Nombre_Local,
    c.precio AS Precio_Base,
    COALESCE(p.descuento, 0) AS Descuento,
    CONCAT(u.nombre, ' ', u.primerApellido, ' ', u.segundoApellido) AS Nombre_Usuario
FROM 
    reservas r
JOIN 
    detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
JOIN 
    canchas c ON dr.ID_Cancha = c.ID_Cancha
JOIN 
    locales l ON c.ID_Local = l.ID_Local
LEFT JOIN 
    precios p ON p.ID_Local = l.ID_Local
    AND r.Fecha_Reserva BETWEEN p.fecha_inicio AND p.fecha_fin
JOIN 
    users u ON r.id = u.id
WHERE 
    r.Fecha_Reserva BETWEEN ? AND ?
    AND r.Estado_Reserva = 4;
",[
                            $request->fecha_inicio,
                            $request->fecha_fin
                        ]); 

        // Generar el PDF
        $pdf = PDF::loadView('.pages.reportes.ingresos', compact('reservas'));
    
        return $pdf->stream();
    }
    


}
