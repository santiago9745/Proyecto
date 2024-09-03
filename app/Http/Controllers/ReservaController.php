<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class ReservaController extends Controller
{
    public function index(){
        $sql=DB::select("SELECT L.ID_Local,L.nombre,L.direccion
                        FROM locales L
                        WHERE L.estado=1");
        $canchas=DB::select("SELECT *
                        FROM canchas
                        WHERE estado=1 AND estado_cancha LIKE 'DISPONIBLE'");
        return view("welcome")->with(['sql' => $sql, 'canchas'=>$canchas]);
    }
    public function reserva(Request $request){
        $reservas = $request->input('reservas', []);

        DB::beginTransaction();
        try {
            foreach ($reservas as $reserva) {
                // Insertar reseerva
                $reservaId = DB::table('reservas')->insertGetId([
                    'Fecha_Reserva' => $reserva['fecha'],
                    'Hora_Inicio' => $reserva['horaInicio'],
                    'Hora_Fin' => $reserva['horaFin']
                ]);

                // Relacionar cancha con tipo
                DB::table('detalle_reserva')->insert([
                    'ID_Cancha' => $reserva['canchas'],
                    'ID_Reserva' => $reservaId,
                ]);
            }

        DB::commit();
        return back()->with('success', 'Canchas agregadas correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al agregar las canchas: ' . $e->getMessage());
        }
    }
}
