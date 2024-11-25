<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificarPromocion extends Notification
{
    use Queueable;

    protected $promocion;
    protected $nombreUsuario;

    /**
     * Create a new notification instance.
     */
    public function __construct($promocion, $nombreUsuario,$IdLocal)
    {
        $this->promocion = $promocion;
        $this->nombreUsuario = $nombreUsuario;
        $this->IdLocal = $IdLocal;
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
            'promocion_id' => $this->promocion->ID_Precio,
            'descuento' => $this->promocion->descuento,
            'fecha_inicio' => $this->promocion->Fecha_Inicio,
            'fecha_fin' => $this->promocion->Fecha_Fin,
            'nombre_local' => $this->promocion->nombre_local,
            'nombre_cancha' => $this->promocion->nombre_cancha,
            'mensaje' => "¡Hola {$this->nombreUsuario}, hay una nueva promoción en tu local {$this->promocion->nombre_local} para la cancha {$this->promocion->nombre_cancha}!",
            'url' => "listadoCanchas-{$this->IdLocal}"
        ];
    }
}
