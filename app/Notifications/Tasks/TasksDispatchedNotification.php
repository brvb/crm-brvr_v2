<?php

namespace App\Notifications\Tasks;

use App\Models\Tenant\Tasks;
use Illuminate\Bus\Queueable;
use App\Mail\Tasks\TaskDispatched;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TasksDispatchedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toMail($notifiable)
    //public function toMail($notifiable)
    {
        // Mail::to("joaomendesgpro@gmail.com")->queue(new TaskDispatched(Tasks::first()))
        //      ->subject("Dispatch de uma task")
        //      ->markdown('tenant.mail.tasks.tasksDispatchedMail', [
        //          'shopInfo' => $this->shopInfo,
        //          'link' => $this->link,
        //          'token' => $this->token,
        //          'clientEmail' => $this->clientEmail
        //      ]);

        // return (new MailMessage)
        //     ->subject('Dispatch de uma Task')
        //     ->markdown('tenant.mail.tasks.tasksDispatchedMail');

        // return (new MailMessage)->queue((new TaskDispatched($this->task))
        // ->to($notifiable));
        // return (new TaskDispatched($this->task))
        //         ->to($notifiable);

//         $d = (new MailMessage)
//             ->subject("asd")
//             ->line('The introduction to the notification.')
//             ->action('Notification Action', url('/'))
//             ->line('Thank you for using our application!');

// dd($d);
//             return $d;
//         #Mail::to("mf@miguelfonseca.pt")->queue(new TaskDispatched(Tasks::first()));
//         $message = "Tarefa ";
//         $message .= 11;
//         $message .= " registada no sistema.";

//         return [
//             #'type'          => config('notificationTypes.new_assistance.key'),
//             'text'          => $message,
//         ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

}
