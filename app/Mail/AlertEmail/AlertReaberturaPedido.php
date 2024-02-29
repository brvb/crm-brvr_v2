<?php

namespace App\Mail\AlertEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertReaberturaPedido extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $cliente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedido,$cliente)
    {
        $this->pedido = $pedido;
        $this->cliente = $cliente;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
          //env('MAIL_USERNAME')

        // $subject = 'RECLAMAÇÃO do pedido #' . $this->pedido->reference . '';
        $subject = '🔴 RECLAMAÇÃO 🔴 do pedido #' . $this->pedido->reference . '';
        return new Envelope(
            subject: $subject,
            from: new Address("fsdfss@gmail.com", session('sender_name')),
        );
    }

  

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    // public function attachments()
    // {
    //     return [];
    // }

    public function build()
    {
        $subject = 'Reabertura do pedido #' . $this->pedido->reference . '';

        $email = $this
            ->view('tenant.mail.alertemail.alertreaberturapedido',[
                "subject" => $subject,
                "task" => $this->pedido,
                "company_name" => session('company_name'),
                "vat" => session('vat'),
                "contact" => session('contact'),
                "email" => session('email'),
                "address" => session('address'),
                "logotipo" => session('logotipo'),
            ]);

            

        return $email;
    }

}
