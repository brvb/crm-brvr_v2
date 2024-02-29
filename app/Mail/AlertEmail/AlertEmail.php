<?php

namespace App\Mail\AlertEmail;

use App\Models\Tenant\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerService;
    public $cliente;
    public $clienteLocation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerService,$cliente,$clienteLocation)
    {
        $this->customerService = $customerService;
        $this->cliente = $cliente;
        $this->clienteLocation = $clienteLocation;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $teste = Config::first();
        $subject = 'Alerta para a conclusão de serviço.';
        return new Envelope(
            subject: $subject,
            from: new Address($teste->email, session('sender_name')),
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
        $customerService = $this->customerService;



        $config = Config::first();
       
        $subject = 'Alerta para a conclusão de serviço.';

        $email = $this
            ->view('tenant.mail.alertemail.alertemail',[
                "subject" => $subject,
                "customerService" => $customerService,
                "company_name" => $config->company_name,
                "vat" => $config->vat,
                "contact" => $config->contact,
                "email" => $config->email,
                "address" => $config->address,
                "logotipo" => $config->logotipo,
            ]);

        return $email;
    }

}
