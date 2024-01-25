<?php

namespace App\Listeners\TeamMember;

use App\Mail\TeamMember\TeamMember;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTeamMemberNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

   
    public function handle(TeamMemberEvent $teamMemberEvent)
    {
        $teamMember = $teamMemberEvent->user;
        if($teamMember->email == "")
        {
            Mail::to("joao.mendes@brvr.pt")->queue(new TeamMember($teamMember));
        }
        else {
            Mail::to($teamMember->email)->queue(new TeamMember($teamMember));
        }
        
    }
}
