<?php

namespace App\Mail\Tasks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

use Illuminate\Queue\SerializesModels;

class TaskReportFinished extends Mailable
{
    use Queueable, SerializesModels;

    public ?object $tasksReports = NULL;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tasksReports)
    {
        $this->tasksReports = $tasksReports;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        //env('MAIL_USERNAME')
        $subject = 'Relatório da Tarefa #' . $this->tasksReports->reference . ' finalizado.';
        return new Envelope(
            subject: $subject,
            from: new Address(env('MAIL_USERNAME'), session('sender_name')),
        );
    }

    // /**
    //  * Get the message content definition.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Content
    //  */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array
    //  */
    // public function attachments()
    // {
    //     return [];
    // }

    public function build()
    {
        $subject = 'Relatório da Tarefa #' . $this->tasksReports->reference . ' finalizado.';

        $email = $this
            ->view('tenant.mail.tasks.task-dispatched',[
                "subject" => $subject,
                "task" => $this->tasksReports,
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
