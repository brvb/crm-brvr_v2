<?php

namespace App\Mail\TeamMember;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeamMember extends Mailable
{
    use Queueable, SerializesModels;

    public $teamMember;
    public $memberPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($teamMember)
    {
        $this->teamMember = $teamMember;
        $this->memberPassword = $teamMember->user;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $subject = 'Conta criada com sucesso.';
        return new Envelope(
            subject: $subject,
            //session('email')
            //env('MAIL_USERNAME')
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
        $teamMember = $this->teamMember;
        $teamMemberPassword = $this->memberPassword;
        $subject = 'Conta criada com sucesso.';

        $email = $this
            ->view('tenant.mail.teammember.teammember',[
                "subject" => $subject,
                "teamMember" => $teamMember,
                "teamMemberPassword" => $teamMemberPassword,
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
