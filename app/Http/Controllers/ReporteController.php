<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;
use Illuminate\Support\facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReporteController extends Controller
{
    public function canchasLocal(){
        $local = auth()->user()->local;
        $canchas = DB::select("SELECT ID_Cancha,nombre
                                FROM canchas
                                WHERE ID_Local=$local");
        return view(".pages.reportes")->with('canchas', $canchas);
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
    -- Calcular el porcentaje de ocupación asegurando que las diferencias sean positivas
    (SUM(TIMESTAMPDIFF(MINUTE, R.Hora_Inicio, R.Hora_Fin)) / 60) / (
        (DATEDIFF(?, ?) + 1) * 
        ABS(TIMESTAMPDIFF(HOUR, L.Hora_Apertura, L.Hora_Cierre))
    ) * 100 AS porcentaje_ocupacion
FROM 
    canchas C
INNER JOIN 
    detalle_reserva DR ON C.ID_Cancha = DR.ID_Cancha
INNER JOIN 
    reservas R ON DR.ID_Reserva = R.ID_Reserva
INNER JOIN 
    locales L ON C.ID_Local = L.ID_Local
WHERE 
    R.Fecha_Reserva BETWEEN ? AND ?
    AND C.ID_Local = $local
    AND R.Estado_Reserva = 1
GROUP BY 
    C.ID_Cancha
ORDER BY 
    numero_reservas DESC;
", [ $fechaFin,$fechaInicio, $fechaInicio, $fechaFin]);
        $pdf = Pdf::loadView('.pages.reportes.utilizacionCanchas', compact('utilizacionCanchas', 'fechaInicio', 'fechaFin'));
        return $pdf->stream();
    }
    public function reporteUsuarios(Request $request)
    {
        // Validamos que se reciban las fechas de inicio y fin
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Obtenemos las fechas de inicio y fin del request
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $idLocal = auth()->user()->local;
        // Consulta SQL con filtrado por rango de fechas
        $usuarios = DB::select("SELECT u.id, u.nombre AS nombre_usuario, u.email, COUNT(r.ID_Reserva) AS total_reservas
            FROM users u
            INNER JOIN reservas r ON u.id = r.id
            INNER JOIN detalle_reserva DR ON r.ID_Reserva=DR.ID_Reserva
            INNER JOIN canchas C ON C.ID_Cancha=DR.ID_Cancha
            INNER JOIN locales l ON C.ID_Local = l.ID_Local
            WHERE r.Estado_Reserva = 1
            AND r.fecha_reserva BETWEEN ? AND ? -- Filtrado por rango de fechas
            AND l.ID_Local = ?
            GROUP BY u.id, u.nombre, u.email, l.nombre
            ORDER BY total_reservas DESC
        ", [$fechaInicio, $fechaFin, $idLocal]);


        // Cargar la vista PDF con los usuarios filtrados
        $pdf = Pdf::loadView('pages.reportes.reporteUsuarios', compact('usuarios', 'fechaInicio', 'fechaFin'));

        // Retornar el PDF para ser visualizado
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
    public function reporteIngresos(Request $request){
        $local = auth()->user()->local;
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
     precios p ON p.ID_Cancha = c.ID_Cancha
     AND r.Fecha_Reserva BETWEEN p.fecha_inicio AND p.fecha_fin
 JOIN 
     users u ON r.id = u.id
 WHERE 
     r.Fecha_Reserva BETWEEN ? AND ?
     AND r.Estado_Reserva = 4 AND l.ID_Local=?;
 ",[
                             $request->fecha_inicio,
                             $request->fecha_fin,
                             $local
                         ]); 
 
         // Generar el PDF
         $pdf = PDF::loadView('.pages.reportes.ingresos', compact('reservas'));
     
         return $pdf->stream();
     }
     public function rangoHorario(Request $request)
{
    // Obtén el ID del local del usuario autenticado
    $local = auth()->user()->local;

    // Construye la consulta SQL con los filtros de fecha y cancha
    $reportes = DB::select("WITH TotalReservasPorCancha AS (
                                SELECT
                                    c.nombre AS cancha,
                                    COUNT(*) AS total_reservas
                                FROM
                                    reservas r
                                INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                                INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                                WHERE
                                    c.ID_Local = ?
                                    AND (? IS NULL OR c.ID_Cancha = ?)
                                    AND r.Fecha_Reserva BETWEEN ? AND ?
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
                                c.ID_Local = ?
                                AND (? IS NULL OR c.ID_Cancha = ?)
                                AND r.Fecha_Reserva BETWEEN ? AND ?
                            GROUP BY
                                t.cancha, rango_horario;",
        [
            $local,
            $request->cancha,
            $request->cancha,
            $request->fecha_inicio,
            $request->fecha_fin,
            $local,
            $request->cancha,
            $request->cancha,
            $request->fecha_inicio,
            $request->fecha_fin
        ]);

    // Genera el PDF usando los datos filtrados
    $pdf = Pdf::loadView('pages.reportes.canchasRangoHorario', compact('reportes'));

    return $pdf->stream();
}

}
