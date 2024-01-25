<?php

namespace App\Events\Tasks;

use App\Models\Tenant\TasksReports;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DispatchTaskReport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tasksReports;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TasksReports $tasksReports)
    {
        $this->tasksReports = $tasksReports;
    }

}
