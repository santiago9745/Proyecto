<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function index(){    
        $locales = DB::select("SELECT
                                L.ID_Local,
                                L.nombre,
                                L.direccion,
                                M.URL
                            FROM
                                locales L
                            LEFT JOIN
                                multimedia M ON M.ID_Local = L.ID_Local
                                AND M.ID_Multimedia = (
                                    SELECT MAX(M2.ID_Multimedia)
                                    FROM multimedia M2
                                    WHERE M2.ID_Local = L.ID_Local
                                )
                            WHERE
                                L.estado = 1;");

    
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
    public function Reservas(){
        $idlocal = auth()->user()->local;
    $idUsuario = auth()->user()->id;

    // Si el usuario tiene un local asignado
    if (!empty($idlocal)) {
        $sql = DB::select("SELECT r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva
                            FROM locales l
                            JOIN canchas c ON l.ID_Local = c.ID_Local
                            JOIN detalle_reserva dr ON c.ID_Cancha = dr.ID_Cancha
                            JOIN reservas r ON dr.ID_Reserva = r.ID_Reserva
                            WHERE l.ID_Local = ?", [$idlocal]);

    // Si el usuario NO tiene un local asignado
    } else {
        $sql = DB::select("SELECT r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva
                            FROM reservas r
                            WHERE r.id = ?", [$idUsuario]);
    }
        return view('.pages.reservas', ['sql' => $sql]);
    }
    public function update(Request $request, $id)
    {
        // Validar el estado de la reserva
        $request->validate([
            'Estado_Reserva' => 'required|in:Pendiente,Confirmada,Cancelada',
        ]);

        // Actualizar el estado de la reserva
        DB::table('reservas')
            ->where('ID_Reserva', $id)
            ->update(['Estado_Reserva' => $request->Estado_Reserva]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Estado de la reserva actualizado correctamente.');
    }   

}
