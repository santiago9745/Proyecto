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
            $sql=DB::select("SELECT C.ID_Cancha,C.nombre, C.estado_cancha,T.nombre_deporte
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
                    'idUsuario' => $idUsuario
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
    public function update(Request $request){
        $idUsuario = auth()->user()->id;
        try {
            $sql=DB::insert("UPDATE canchas SET nombre=?,estado_cancha=?,fechaModificacion=CURRENT_TIMESTAMP,idUsuario=$idUsuario WHERE ID_Cancha=$request->id",[
                strtoupper($request->nombre),
                strtoupper($request->disponibilidad)
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
                            WHERE C.estado = 1 AND L.ID_Local = $local AND R.estado_reserva = 'Confirmada'  -- Filtra solo reservas confirmadas
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
                                            AND R.Estado_Reserva = 'Confirmada'
                                        GROUP BY C.ID_Cancha
                                        ORDER BY numero_reservas DESC;"
                                        , [$fechaInicio, $fechaFin, $fechaInicio, $fechaFin]);
        $pdf = Pdf::loadView('.pages.reportes.utilizacionCanchas', compact('utilizacionCanchas', 'fechaInicio', 'fechaFin'));
        return $pdf->stream();
    }
}
