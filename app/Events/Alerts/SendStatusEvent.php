<?php

namespace App\Events\Alerts;

use App\Models\Tenant\CustomerServices;
use App\Models\User;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\TasksReports;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pedido;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(object $intervencao)
    {
        $this->pedido = $intervencao;
    }

}
