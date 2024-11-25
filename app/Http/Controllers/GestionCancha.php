<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log; 

class GestionCancha extends Controller
{
    public function index(){
        $local = auth()->user()->local;
        if ($local != "") {
            $sql=DB::select("SELECT C.ID_Cancha,C.nombre, C.estado_cancha,T.nombre_deporte,C.precio
                        FROM canchas C
                        INNER JOIN locales L ON L.ID_Local=C.ID_Local
                        INNER JOIN canchatipo Ct ON C.ID_Cancha=Ct.ID_Cancha
                        INNER JOIN tipo T ON T.ID_Tipo=Ct.ID_Tipo
                        WHERE C.estado=1 AND C.ID_Local=$local");
        } else {
            $sql=0;
        }
        return view(".pages.canchas")->with('sql', $sql);
    }
    public function agregar(Request $request){
            $canchas = $request->input('canchas', []);
            $local = auth()->user()->local;
            $idUsuario = auth()->user()->id;
            DB::beginTransaction();

        try {
            foreach ($canchas as $cancha) {
                // Insertar cancha
                $canchaId = DB::table('canchas')->insertGetId([
                    'nombre' => strtoupper($cancha['nombre']),
                    'estado_cancha' => strtoupper($cancha['disponibilidad']),
                    'ID_Local' => $local,
                    'idUsuario' => $idUsuario,
                    'precio' => $cancha['precio']
                ]);

                // Insertar tipo de deporte
                $tipoId = DB::table('tipo')->insertGetId([
                    'nombre_deporte' => strtoupper($cancha['tipo']),
                ]);

                // Relacionar cancha con tipo
                DB::table('canchatipo')->insert([
                    'ID_Cancha' => $canchaId,
                    'ID_Tipo' => $tipoId,
                    'tarifa' => 0
                ]);
            }

            DB::commit();

                return back()->with('success', 'Canchas agregadas correctamente');
            } catch (\Exception $e) {
                DB::rollBack();

                return back()->with('error', 'Error al agregar las canchas: ' . $e->getMessage());
            }
    }
    public function delete($id){
        try {
            $sql=DB::insert("UPDATE canchas SET estado=0 WHERE ID_Cancha=$id");
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
    public function getCanchaByLocalId($localId){
        if ($localId != "") {
            $idUsuario = auth()->user()->id;
            $horariosReservados=0;
            $canchas=DB::select("SELECT C.ID_Cancha,C.nombre, C.estado_cancha,T.nombre_deporte,C.precio,L.ID_Local,L.Hora_Apertura, L.Hora_Cierre
                        FROM canchas C
                        INNER JOIN locales L ON L.ID_Local=C.ID_Local
                        INNER JOIN canchatipo Ct ON C.ID_Cancha=Ct.ID_Cancha
                        INNER JOIN tipo T ON T.ID_Tipo=Ct.ID_Tipo
                        WHERE C.estado=1 AND C.ID_Local=$localId");
            
                        foreach ($canchas as $cancha) {
                            $cancha->imagenes = DB::select("SELECT M.URL
                                                            FROM multimedia M
                                                            WHERE M.ID_Cancha = ?", [$cancha->ID_Cancha]);
                        }
            $reservas=DB::select("SELECT RT.ID_Cancha,C.nombre,RT.ID_Local,RT.ID_Usuario,RT.Fecha_Reserva,RT.Hora_Inicio,RT.Hora_Fin,RT.precio
                                    FROM reservas_temporal RT
                                    INNER JOIN canchas C ON C.ID_Cancha=RT.ID_Cancha
                                    WHERE ID_Usuario=$idUsuario");
        } else {
            $canchas=0;
        }
        return view(".pages.canchas-by-locales")->with('canchas', $canchas)->with('reservas', $reservas);
    }
    public function getHorariosOcupados(Request $request)
{
    $idUsuario = auth()->user()->id;
    // Obtener los horarios reservados
    $horariosReservados = DB::select("SELECT R.Hora_Inicio, R.Hora_Fin,C.ID_Cancha 
                                       FROM canchas C
                                       INNER JOIN detalle_reserva DR ON DR.ID_Cancha = C.ID_Cancha
                                       INNER JOIN reservas R ON R.ID_Reserva = DR.ID_Reserva
                                       WHERE C.ID_Cancha = ? AND R.Fecha_Reserva = ? AND R.Estado_Reserva=1", [
                                           $request->id,
                                           $request->fechaReserva
                                       ]);

    // Obtener las canchas para enviarlas a la vista
    $canchas = DB::select("SELECT C.ID_Cancha, C.nombre, C.estado_cancha, T.nombre_deporte, C.precio,L.ID_Local,L.Hora_Apertura, L.Hora_Cierre
                            FROM canchas C
                            INNER JOIN locales L ON L.ID_Local = C.ID_Local
                            INNER JOIN canchatipo Ct ON C.ID_Cancha = Ct.ID_Cancha
                            INNER JOIN tipo T ON T.ID_Tipo = Ct.ID_Tipo
                            WHERE C.estado = 1 AND C.ID_Local=?",[$request->idLocal]);
    $reservas=DB::select("SELECT RT.ID_Cancha,C.nombre,RT.ID_Local,RT.ID_Usuario,RT.Fecha_Reserva,RT.Hora_Inicio,RT.Hora_Fin,RT.precio
                        FROM reservas_temporal RT
                        INNER JOIN canchas C ON C.ID_Cancha=RT.ID_Cancha
                        WHERE ID_Usuario=$idUsuario");
    foreach ($canchas as $cancha) {
        $cancha->imagenes = DB::select("SELECT M.URL
                                          FROM multimedia M
                                          WHERE M.ID_Cancha = ?", [$cancha->ID_Cancha]);
    }
    
    session(['modal_open' => true, 'id_cancha' => $request->id, 'fecha_reserva' => $request->fechaReserva]);
    return view(".pages.canchas-by-locales", compact('horariosReservados', 'canchas','reservas'));
}
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'cancha_id' => 'required|integer',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'precio' => 'required|numeric',
            'idUsuario' => 'required|integer',
            'idLocal' => 'required|integer',
        ]);

        $reservaExistente = DB::table('reservas_temporal')
        ->where('ID_Cancha', $request->cancha_id)
        ->where('Fecha_Reserva', $request->fecha)
        ->where(function ($query) use ($request) {
            $query->whereBetween('Hora_Inicio', [$request->hora_inicio, $request->hora_fin])
                  ->orWhereBetween('Hora_Fin', [$request->hora_inicio, $request->hora_fin])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('Hora_Inicio', '<=', $request->hora_inicio)
                            ->where('Hora_Fin', '>=', $request->hora_fin);
                  });
        })
        ->exists();
        if (!$reservaExistente) {
            $reservaExistente = DB::table('reservas')
                ->join('detalle_reserva', 'reservas.ID_Reserva', '=', 'detalle_reserva.ID_Reserva') // Join entre reservas y detalle_reserva
                ->where('detalle_reserva.ID_Cancha', $request->cancha_id) // Verificar el ID_Cancha en detalle_reserva
                ->where('reservas.Fecha_Reserva', $request->fecha) // Verificar la fecha en reservas
                ->where(function ($query) use ($request) {
                    $query->whereBetween('reservas.Hora_Inicio', [$request->hora_inicio, $request->hora_fin])
                        ->orWhereBetween('reservas.Hora_Fin', [$request->hora_inicio, $request->hora_fin])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('reservas.Hora_Inicio', '<=', $request->hora_inicio)
                                    ->where('reservas.Hora_Fin', '>=', $request->hora_fin);
                        });
                })
                ->first();
            if ($reservaExistente) {
                // Construimos un mensaje indicando la reserva existente
                $mensaje = "La cancha ya está reservada desde " . $reservaExistente->Hora_Inicio . 
                          " hasta " . $reservaExistente->Hora_Fin . " para la fecha " . 
                           $reservaExistente->Fecha_Reserva . ". puede reservar para entes de las ". $reservaExistente->Hora_Inicio . " o para despues de las ".$reservaExistente->Hora_Fin;
            }

                    
        }
    
        if ($reservaExistente) {
            // Redirigir con mensaje de error si ya hay una reserva para la misma fecha y hora
            return redirect()->route('getCanchaByLocalId', ['id' => $request->idLocal])->with('error', $mensaje);
        }
        Log::info('Nueva reserva creada:', [
            'Hora_Inicio' => $request->hora_inicio,
            'Hora_Fin' => $request->hora_fin,
        ]);
        // Insertar la reserva en la tabla temporal
        $sql = DB::insert(
            "INSERT INTO reservas_temporal (ID_Cancha, Fecha_Reserva, Hora_Inicio, Hora_Fin, precio, ID_Usuario, ID_Local) VALUES (?, ?, ?, ?, ?, ?, ?)", [
                $request->cancha_id,
                $request->fecha,
                $request->hora_inicio,
                $request->hora_fin,
                $request->precio,
                $request->idUsuario,
                $request->idLocal,
            ]
        );

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('getCanchaByLocalId', ['id' => $request->idLocal])->with('success', 'Reserva añadida temporalmente.');
    }
    public function moveReservationsToPermanent()
    {
        $idUsuario = auth()->user()->id;
        DB::beginTransaction();

        try {
            // Obtenemos las reservas de la tabla temporal solo para el usuario especificado
            $reservasTemporales = DB::table('reservas_temporal')
                ->where('ID_Usuario', $idUsuario)
                ->get();
                $reservas = DB::select("SELECT 
                CONCAT(U.nombre, ' ', U.primerApellido, ' ', U.segundoApellido) AS nombre_cliente, 
                C.nombre AS nombre_cancha,
                RT.Fecha_Reserva, 
                RT.Hora_Inicio, 
                RT.Hora_Fin,
                L.nombre AS nombre_local, 
                U.email AS email_cliente,
                RT.precio AS precio_por_30_mins,
                CEIL(TIMESTAMPDIFF(MINUTE, RT.Hora_Inicio, RT.Hora_Fin) / 30) * RT.precio AS total_por_reserva
            FROM reservas_temporal RT
            INNER JOIN users U ON U.id = RT.ID_Usuario
            INNER JOIN locales L ON L.ID_Local = RT.ID_Local
            INNER JOIN canchas C ON C.ID_Cancha=RT.ID_Cancha
            WHERE RT.ID_Usuario = ?;", [$idUsuario]);
            foreach ($reservasTemporales as $reserva) {
                // Insertamos en la tabla `reservas`
                $idReserva = DB::table('reservas')->insertGetId([
                    'Fecha_Reserva' => $reserva->Fecha_Reserva,
                    'Hora_Inicio' => $reserva->Hora_Inicio,
                    'Hora_Fin' => $reserva->Hora_Fin,
                    'id' => $reserva->ID_Usuario,
                    'Estado_Reserva' => 2, // o cualquier estado inicial
                    'fecha_creacion' => now(),
                ]);

                // Insertamos en la tabla `detalle_reserva` con el ID_Reserva y ID_Cancha
                DB::table('detalle_reserva')->insert([
                    'ID_Reserva' => $idReserva,
                    'ID_Cancha' => $reserva->ID_Cancha,
                ]);
            }

            // Limpiamos las reservas temporales del usuario después de mover los datos
            DB::table('reservas_temporal')
                ->where('ID_Usuario', $idUsuario)
                ->delete();

            // Si todo ha ido bien, confirmamos la transacción
            DB::commit();
            $pdf = Pdf::loadView('.pages.reportes.cotizacion', compact('reservas'));
            return $pdf->stream();
        } catch (\Exception $e) {
            // Si ocurre algún error, revertimos la transacción
            DB::rollback();

            return redirect()->back()->with('error', 'Ocurrió un error al mover las reservas: ' . $e->getMessage());
        }
    }
    public function update(Request $request){
        $idUsuario = auth()->user()->id;
        try {
            $sql=DB::insert("UPDATE canchas SET nombre=?,estado_cancha=?,fechaModificacion=CURRENT_TIMESTAMP,idUsuario=$idUsuario, precio=? WHERE ID_Cancha=$request->id",[
                strtoupper($request->nombre),
                strtoupper($request->disponibilidad),
                $request->precio
            ]);
            $sql=DB::insert("UPDATE tipo SET nombre_deporte=? WHERE ID_Tipo=$request->id");
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
    public function pdf(){
        $local = auth()->user()->local;
        $cancha=DB::select("SELECT C.ID_Cancha, C.nombre, C.estado_cancha, T.nombre_deporte, COUNT(DR.ID_Cancha) AS total_reservas
                            FROM canchas C
                            INNER JOIN locales L ON L.ID_Local = C.ID_Local
                            INNER JOIN canchatipo Ct ON C.ID_Cancha = Ct.ID_Cancha
                            INNER JOIN tipo T ON T.ID_Tipo = Ct.ID_Tipo
                            INNER JOIN detalle_reserva DR ON C.ID_Cancha = DR.ID_Cancha
                            INNER JOIN reservas R ON R.ID_Reserva = DR.ID_Reserva
                            WHERE C.estado = 1 AND L.ID_Local = $local AND R.estado_reserva = 1 -- Filtra solo reservas confirmadas
                            GROUP BY C.ID_Cancha, C.nombre, C.estado_cancha, T.nombre_deporte
                            ORDER BY total_reservas DESC;");
        $pdf = Pdf::loadView('.pages.reportes.reporteCanchas', compact('cancha'));
        return $pdf->stream();
    }
    
    
}
