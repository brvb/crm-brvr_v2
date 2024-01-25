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

class AlertEmailConclusionDay extends Mailable
{
    use Queueable, SerializesModels;

    public $infoSendEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($infoSendEmail)
    {
        $this->infoSendEmail = $infoSendEmail;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $infoSendEmail = $this->infoSendEmail;

        $teste = Config::first();
        $subject = 'Relatório de '.$infoSendEmail["nome"].' ('.date('Y-m-d').')';
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
        $infoSendEmail = $this->infoSendEmail;

        $config = Config::first();
       
        $subject = 'Relatório de '.$infoSendEmail["nome"].' ('.date('Y-m-d').')';

        $email = $this
            ->view('tenant.mail.alertemail.alertemailconclusion',[
                "subject" => $subject,
                "infoSendEmail" => $infoSendEmail,
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
