<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaComprobanteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservas;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct($reservas,$pdfContent)
    {
        $this->reservas=$reservas;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reserva Comprobante Mail',
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
        return $this->view('emails.reserva_comprobante')
                    ->subject('Comprobante de Reserva')
                    ->attachData($this->pdfContent, 'comprobante.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
