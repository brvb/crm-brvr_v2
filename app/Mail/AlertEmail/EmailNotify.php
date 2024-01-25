<?php

namespace App\Mail\AlertEmail;

use App\Models\Tenant\Config;
use App\Models\Tenant\Customers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $taskInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($taskInfo)
    {
        $this->taskInfo = $taskInfo;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $teste = Config::first();
        $subject = 'Alerta para a tarefa.';
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
        $taskInfo = $this->taskInfo;

        $config = Config::first();
       
        $subject = 'Alerta para a intervenção.';

        $customer = Customers::where('id',$taskInfo["task"]["customer_id"])->first();

        $email = $this
            ->view('tenant.mail.alertemail.emailnotify',[
                "subject" => $subject,
                "taskInfo" => $taskInfo,
                "company_name" => $config->company_name,
                "customer" => $customer,
                "vat" => $config->vat,
                "contact" => $config->contact,
                "email" => $config->email,
                "address" => $config->address,
                "logotipo" => $config->logotipo,
            ]);

        return $email;
    }

}
