<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function index(){    
        $locales = DB::select("SELECT L.ID_Local, L.nombre, L.direccion
                           FROM locales L
                           WHERE L.estado=1");

    
        foreach ($locales as $local) {
        // Obtener las canchas asociadas a este local
        $local->canchas = DB::select("SELECT *
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
}
