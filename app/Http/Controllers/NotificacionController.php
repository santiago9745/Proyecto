<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionReserva;
use App\Models\User;

class NotificacionController extends Controller
{
    public function index()
    {
        $local = auth()->user()->local;
        $sql = DB::select("SELECT r.ID_Reserva, r.Fecha_Reserva, r.Hora_Inicio, r.Hora_Fin, r.Estado_Reserva,CONCAT(u.nombre, ' ' , u.primerApellido, ' ' , u.segundoApellido) AS nombreCompleto, u.email,
                            c.nombre AS nombre_cancha,
                            l.nombre AS nombre_local, l.direccion AS direccion_local,
                            -- Calculamos los días restantes
                            TIMESTAMPDIFF(DAY, NOW(), CONCAT(r.Fecha_Reserva, ' ', r.Hora_Inicio)) AS dias_restantes,
                            -- Calculamos las horas restantes
                            TIMESTAMPDIFF(HOUR, NOW(), CONCAT(r.Fecha_Reserva, ' ', r.Hora_Inicio)) % 24 AS horas_restantes
                        FROM reservas r
                        INNER JOIN users u ON r.id = u.id
                        INNER JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                        INNER JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                        INNER JOIN locales l ON c.ID_Local = l.ID_Local
                        WHERE l.ID_Local = $local
                        AND r.Estado_Reserva = 'Confirmada'
                        AND r.Fecha_Reserva >= CURDATE();");
        return view(".pages.notificaciones")->with('sql', $sql);
    }
    public function enviarRecordatorio(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'idUsuario' => 'required|integer|exists:users,id',
            'dias' => 'required|integer',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
        ]);

        // Encuentra el usuario por su ID
        $user = User::find($request->idUsuario);

        if ($user) {
            // Envía la notificación con los datos del formulario
            $user->notify(new RecordatorioReserva($request->dias, $request->fecha, $request->hora));

            return redirect()->back()->with('success', 'Recordatorio enviado exitosamente!');
        } else {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }
    }
    public function enviarNotificacion(Request $request)
{
    // El mensaje autogenerado o personalizado ya viene desde la vista
    $mensaje = $request->input('mensaje');
    $email = $request->input('email');

    if ($request->has('mensaje_autogenerado')) {
        $mensaje = $request->input('mensaje_autogenerado');
    } elseif ($request->has('mensaje_personalizado')) {
        $mensaje = $request->input('mensaje_personalizado');
    } else {
        // Manejo de error si no hay mensaje
        return redirect()->back()->with('error', 'No se ha enviado ningún mensaje.');
    }

    Mail::to($email)->send(new NotificacionReserva($mensaje));
    // Por ejemplo, redireccionar de vuelta con una notificación flash:
    return redirect()->back()->with('status', 'Notificación enviada: ' . $mensaje);
}

}
