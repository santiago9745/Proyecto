<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionPromocion extends Mailable
{
    use Queueable, SerializesModels;
    public $promocion;
    public $nombre_usuario;

    /**
     * Create a new message instance.
     */
    public function __construct($promocion, $nombre_usuario)
    {
        $this->promocion = $promocion;
        $this->nombre_usuario = $nombre_usuario;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificacion Promocion',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        return $this->view('emails.notificacion_promocion')
                    ->with([
                        'descuento' => $this->promocion->descuento,
                        'nombre_usuario' => $this->nombre_usuario
                    ]);
    }
}
