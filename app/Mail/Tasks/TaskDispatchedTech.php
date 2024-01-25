<?php

namespace App\Mail\Tasks;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskDispatchedTech extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task = $task->task;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
          //env('MAIL_USERNAME')
        $subject = 'Tarefa #' . $this->task->reference . ' agendada com sucesso.';
        return new Envelope(
            subject: $subject,
            from: new Address(env('MAIL_USERNAME'), session('sender_name')),
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
       
        $subject = 'Tarefa #' . $this->task->reference . ' agendada com sucesso.';

        $email = $this
            ->view('tenant.mail.tasks.task-dispatched',[
                "subject" => $subject,
                "task" => $this->task,
                "company_name" => session('company_name'),
                "vat" => session('vat'),
                "contact" => session('contact'),
                "email" => session('email'),
                "address" => session('address'),
                "logotipo" => session('logotipo'),
            ]);

            foreach($this->task->servicesToDo as $serv)
            {
                if($serv->service->file != null)
                {
                    $arrayFile = json_decode($serv->service->file,true);
                
                    foreach($arrayFile as $file)
                    {
                        $email->attach(config('filesystems.disks.local.root').$file["fileFolder"]);
                    }
                   
                }
            }


            $dateOfBegin = date('Ymd', strtotime($this->task->scheduled_date));
            $dateOfEnding = date('Ymd', strtotime($this->task->scheduled_date));
            $hourOfBegin =  date('His', strtotime($this->task->scheduled_hour) - 3600);
            $hourOfEnding = date('His',strtotime($this->task->scheduled_hour));

            $randomNumber = rand(1,9);
            for($i=0; $i<50;$i++) {
                $randomNumber .= rand(0,9);
            }

            $bodyMessage = "";
            $bodyMessage .= "Tarefa: ".$this->task->reference."=0D=0A";
            $bodyMessage .= "Hora marcada: ".$this->task->scheduled_date." ".$this->task->scheduled_hour."=0D=0A";
            $bodyMessage .= "Cliente: ".$this->task->taskCustomer->name."=0D=0A";
            $bodyMessage .= "Local: ".$this->task->taskLocation->description."=0D=0A";
            $bodyMessage .= "Serviços: ";
            $services = "";
            foreach($this->task->servicesToDo as $service)
            {
                $services .= $service->service->name." | ";
            }
            $bodyMessage .= rtrim($services,"| ");
            
            
                  
            $contentOfFile = "BEGIN:VCALENDAR\n";
            $contentOfFile .= "PRODID:-//Microsoft Corporation//Outlook 16.0 MIMEDIR//EN\n";
            $contentOfFile .= "VERSION:1.0\n";
            $contentOfFile .= "BEGIN:VEVENT\n";
            $contentOfFile .= "DTSTART:".$dateOfBegin."T".$hourOfBegin."Z\n";
            $contentOfFile .= "DTEND:".$dateOfEnding."T".$hourOfEnding."Z\n";
            $contentOfFile .= "UID:".$randomNumber."\n";
            $contentOfFile .= "DESCRIPTION;ENCODING=QUOTED-PRINTABLE:".$bodyMessage."\n";
            $contentOfFile .= "SUMMARY:".$this->task->taskCustomer->name."\n";
            $contentOfFile .= "LOCATION:".$this->task->taskLocation->description."\n";
            $contentOfFile .= "PRIORITY:3\n";
            $contentOfFile .= "END:VEVENT\n";
            $contentOfFile .= "END:VCALENDAR\n";

            File::put('marcacao-'.$this->task->reference.'.vcs',$contentOfFile);

            $email->attach('marcacao-'.$this->task->reference.'.vcs');
           

        return $email;
    }

}
