<?php

namespace App\Listeners\Tasks;

use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Events\Tasks\TaskCreated;
use App\Models\Tenant\TeamMember;
use App\Mail\Tasks\TaskDispatched;
use App\Mail\Tasks\TaskCreateEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskDispatchedTech;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Tasks\DispatchTasksToUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Tasks\TasksDispatchedNotification;

class TaskNotification
{
    use Notifiable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskCreated  $event
     * @return void
     */
    public function handle(TaskCreated $task)
    {
        $teamMember = TeamMember::where('id',$task->taskCreated->tech_id)->first();        
        Mail::to($teamMember->email)->queue(new TaskCreateEmail($task));

        $emailConfig = Config::first();
        Mail::to($emailConfig->email)->queue(new TaskCreateEmail($task));

        
    }
}
