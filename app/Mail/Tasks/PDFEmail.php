<?php

namespace App\Mail\Tasks;

use App\Models\Tenant\TeamMember;
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
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info,$pdf)
    {
        $this->info = $info;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
       
        //env('MAIL_USERNAME')
        $subject = 'O pedido #' . $this->info->reference . ' foi CONCLUÍDO.';

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

               
        $subject = 'Pedido #' . $this->info->reference . ' foi CONCLUÍDA.';

        $tM = TeamMember::where('id',$this->info->tech_id)->first();

        foreach($this->info->intervencoes as $int)
        {
            if(($int->estado_pedido == 2) && ($int->user_id == $tM->user_id))
            {
                $dataInicio = $int->data_inicio;
                $horaInicio = $int->hora_inicio;
            }
        }


        $email = $this
            ->view('tenant.mail.tasks.pdf-email',[
                "subject" => $subject,
                "task" => $this->info,
                "dataInicio" => $dataInicio,
                "horaInicio" => $horaInicio,
                "pdf" => $this->pdf,
                "company_name" => session('company_name'),
                "vat" => session('vat'),
                "contact" => session('contact'),
                "email" => session('email'),
                "address" => session('address'),
                "logotipo" => session('logotipo'),
            ])->bcc(['bruno@brvr.pt']);



        //  if($this->pdf == "1")
        //  {
            $email->attach(global_tenancy_asset('/app/public/pedidos/pdfs_conclusao/'.$this->info->reference.'/'.$this->info->reference.'.pdf'));
        //  }
        //Junta o PDF 
        

        return $email;
    }

}
