<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstadoActualizado extends Notification
{
    use Queueable;

    private $reserva;
    private $estado;
    /**
     * Create a new notification instance.
     */
    public function __construct($reserva, $estado)
    {
        $this->reserva = $reserva;
        $this->estado = $estado;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable) 
    {
        return [
            'reserva_id' => $this->reserva->ID_Reserva,
            'nombreLocal' => $this->reserva->nombre_local,
            'estado' => $this->estado,
            'url'=> 'reservas',
            'mensaje' => "El estado de tu reserva en el local {$this->reserva->nombre_local} ha sido actualizado a: ",
        ];
    }
    private function getEstadoNombre($estado)
    {
        // Cambia esto según los nombres reales de los estados
        return match ($estado) {
            0 => 'Cancelada',
            1 => 'Confirmada',
            2 => 'Pendiente',
            3 => 'Confirmada',
            default => 'Estado desconocido', 
        };
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
