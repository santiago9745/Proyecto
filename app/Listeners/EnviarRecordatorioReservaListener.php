<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionReserva;
use App\Events\RecordatorioReservaEvent;
use Illuminate\Support\Facades\Log;

class EnviarRecordatorioReservaListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecordatorioReservaEvent $event)
    {
        Log::info('El listener de RecordatorioReserva se está ejecutando.');
        // Obtener todas las reservas que sean dentro de 3 días
        foreach ($event->reservas as $reserva) {
            $mensaje = "Estimado/a {$reserva->nombre_completo}, su reserva en {$reserva->nombre_cancha} el día {$reserva->Fecha_Reserva} está próxima. ¡Le esperamos!";
            Mail::to($reserva->email)->send(new NotificacionReserva($mensaje));
        }
    }


}
