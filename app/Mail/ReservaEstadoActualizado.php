<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaEstadoActualizado extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;
    public $estado;

    /**
     * Create a new message instance.
     */
    public function __construct($reserva, $estado)
    {
        $this->reserva = $reserva;
        $this->estado = $estado;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reserva Estado Actualizado',
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
        return $this->subject('Actualización de Estado de la Reserva')
                    ->view('emails.reserva_estado_actualizado');
    }
}
