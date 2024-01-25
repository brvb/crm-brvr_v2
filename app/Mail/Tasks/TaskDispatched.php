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

class TaskDispatched extends Mailable
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

            

        return $email;
    }

}
