<?php

namespace App\Events\Tasks;

use App\Models\Tenant\Tasks;
use App\Models\Tenant\TasksReports;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $taskCreated;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tasks $task)
    {
        $this->taskCreated = $task;
    }

}
