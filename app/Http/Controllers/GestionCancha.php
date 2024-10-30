<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $canchas=DB::select("SELECT C.ID_Cancha,C.nombre, C.estado_cancha,T.nombre_deporte,C.precio
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
        } else {
            $canchas=0;
        }
        return view(".pages.canchas-by-locales")->with('canchas', $canchas);
    }
    public function getHorariosOcupados(Request $request)
{
    // Obtener los horarios reservados
    $horariosReservados = DB::select("SELECT R.Hora_Inicio, R.Hora_Fin,C.ID_Cancha 
                                       FROM canchas C
                                       INNER JOIN detalle_reserva DR ON DR.ID_Cancha = C.ID_Cancha
                                       INNER JOIN reservas R ON R.ID_Reserva = DR.ID_Reserva
                                       WHERE C.ID_Cancha = ? AND R.Fecha_Reserva = ?", [
                                           $request->id,
                                           $request->fechaReserva
                                       ]);

    // Obtener las canchas para enviarlas a la vista
    $canchas = DB::select("SELECT C.ID_Cancha, C.nombre, C.estado_cancha, T.nombre_deporte, C.precio
                            FROM canchas C
                            INNER JOIN locales L ON L.ID_Local = C.ID_Local
                            INNER JOIN canchatipo Ct ON C.ID_Cancha = Ct.ID_Cancha
                            INNER JOIN tipo T ON T.ID_Tipo = Ct.ID_Tipo
                            WHERE C.estado = 1");

    foreach ($canchas as $cancha) {
        $cancha->imagenes = DB::select("SELECT M.URL
                                          FROM multimedia M
                                          WHERE M.ID_Cancha = ?", [$cancha->ID_Cancha]);
    }
    session(['modal_open' => true, 'id_cancha' => $request->id]);
    return view(".pages.canchas-by-locales", compact('horariosReservados', 'canchas'));
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
    public function canchasUtilidad(Request $request){
        $local = auth()->user()->local;
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);
    
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
    
        $utilizacionCanchas = DB::select("SELECT 
                                            C.nombre AS nombre_cancha,
                                            COUNT(DR.ID_DetalleReserva) AS numero_reservas,
                                            SUM(TIMESTAMPDIFF(MINUTE, R.Hora_Inicio, R.Hora_Fin)) / 60 AS horas_utilizacion,
                                            (SUM(TIMESTAMPDIFF(MINUTE, R.Hora_Inicio, R.Hora_Fin)) / 60 / 
                                                (ABS(DATEDIFF(?, ?)) * 24)) * 100 AS porcentaje_ocupacion
                                        FROM canchas C
                                        INNER JOIN detalle_reserva DR ON C.ID_Cancha = DR.ID_Cancha
                                        INNER JOIN reservas R ON DR.ID_Reserva = R.ID_Reserva
                                        WHERE 
                                            R.Fecha_Reserva BETWEEN ? AND ?
                                            AND C.ID_Local = $local
                                            AND R.Estado_Reserva = 1
                                        GROUP BY C.ID_Cancha
                                        ORDER BY numero_reservas DESC;"
                                        , [$fechaInicio, $fechaFin, $fechaInicio, $fechaFin]);
        $pdf = Pdf::loadView('.pages.reportes.utilizacionCanchas', compact('utilizacionCanchas', 'fechaInicio', 'fechaFin'));
        return $pdf->stream();
    }
    public function descuentos()
    {
        $local = auth()->user()->local;
        $canchasConDescuento = DB::select("SELECT C.nombre AS nombre_cancha,C.estado_cancha,P.descuento,P.Fecha_Inicio,P.Fecha_Fin
                                            FROM canchas C
                                            INNER JOIN locales L ON C.ID_Local= L.ID_Local
                                            LEFT JOIN precios P ON P.ID_Cancha=C.ID_Cancha
                                            WHERE L.ID_Local=?
                            ",[$local]);
        $pdf = Pdf::loadView('.pages.reportes.promocion', compact('canchasConDescuento'));
        return $pdf->stream();
    }
    public function rangoHorario(){
        $local = auth()->user()->local;
        $reportes = DB::select("WITH TotalReservasPorCancha AS (
                                    SELECT
                                        c.nombre AS cancha,
                                        COUNT(*) AS total_reservas
                                    FROM
                                        reservas r
                                    INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                                    INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                                    WHERE
                                        c.ID_Local = $local
                                    GROUP BY
                                        c.nombre
                                )
                                SELECT
                                    t.cancha,
                                    CONCAT(LPAD(HOUR(r.Hora_Inicio), 2, '0'), ':', LPAD(MINUTE(r.Hora_Inicio), 2, '0'), ' - ', 
                                        LPAD(HOUR(r.Hora_Fin), 2, '0'), ':', LPAD(MINUTE(r.Hora_Fin), 2, '0')) AS rango_horario,
                                    COUNT(*) AS total_reservas_rango,
                                    CONCAT(ROUND((COUNT(*) / t.total_reservas) * 100, 2), '%') AS porcentaje_utilizacion
                                FROM
                                    reservas r
                                INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                                INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                                INNER JOIN TotalReservasPorCancha t ON c.nombre = t.cancha
                                WHERE
                                    c.ID_Local = $local
                                GROUP BY
                                    t.cancha, rango_horario;
                                ");
        $pdf = Pdf::loadView('.pages.reportes.canchasRangoHorario', compact('reportes'));
        return $pdf->stream();
    }
}
