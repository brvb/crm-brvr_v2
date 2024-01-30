<?php

namespace App\Mail\Tasks;

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

class PDFEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
       
        //env('MAIL_USERNAME')
        $subject = 'Pedido #' . $this->info->reference . ' pedido finalizado com sucesso.';

        return new Envelope(
            subject: $subject,
            from: new Address("pp@gmail.com", session('sender_name')),
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

               
        $subject = 'Pedido #' . $this->info->reference . ' pedido finalizado com sucesso.';

        $email = $this
            ->view('tenant.mail.tasks.pdf-email',[
                "subject" => $subject,
                "task" => $this->info,
                "company_name" => session('company_name'),
                "vat" => session('vat'),
                "contact" => session('contact'),
                "email" => session('email'),
                "address" => session('address'),
                "logotipo" => session('logotipo'),
            ]);

        //Junta o PDF 
        $email->attach(global_tenancy_asset('/app/public/pedidos/pdfs_conclusao/'.$this->info->reference.'/'.$this->info->reference.'.pdf'));

        return $email;
    }

}
