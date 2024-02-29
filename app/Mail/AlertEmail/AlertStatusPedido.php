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

class AlertStatusPedido extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $cliente;
    public $mensagem;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedido,$cliente,$mensagem)
    {
        $this->pedido = $pedido;
        $this->cliente = $cliente;
        $this->mensagem = $mensagem;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
          //env('MAIL_USERNAME')

        $subject = 'INFORMAÇÕES do pedido #' . $this->pedido->reference . ' - '.$this->pedido->tipoEstado->nome_estado;
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
        

        $email = $this
            ->view('tenant.mail.alertemail.alertstatuspedido',[
                "task" => $this->pedido,
                "mensagem" => $this->mensagem,
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
