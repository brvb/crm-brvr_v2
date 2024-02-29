<?php

namespace App\Mail\AlertEmail;

use App\Models\Tenant\Config;
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

class AlertCheckFinalizados extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $intervencao;
    public $cst;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedido, $intervencao,$cst)
    {
        $this->pedido = $pedido;
        $this->intervencao = $intervencao;
        $this->cst = $cst;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
          //env('MAIL_USERNAME')
        $subject = 'O pedido #' . $this->pedido->reference . ' FOI FINALIZADO';
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
        $config = Config::first();
        $email = $this
            ->view('tenant.mail.alertemail.alertemailfinalizados',[
                "cst" => $this->cst,
                "task" => $this->pedido,
                "intervencoes" => $this->intervencao,
                "company_name" => $config->company_name,
                "vat" => $config->vat,
                "contact" =>$config->contact,
                "email" => $config->email,
                "address" =>$config->address,
                "logotipo" => $config->logotipo,
            ]);

        return $email;
    }

}
