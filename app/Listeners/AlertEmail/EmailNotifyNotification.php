<?php

namespace App\Listeners\AlertEmail;

use App\Models\Tenant\Config;
use App\Models\Tenant\Customers;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use App\Mail\AlertEmail\EmailNotify;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Events\Alerts\EmailNotifyEvent;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Tenant\CustomerNotifications;

class EmailNotifyNotification
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

   
    public function handle(EmailNotifyEvent $alertNotifyEvent)
    {
       $infoTask = json_decode(json_encode($alertNotifyEvent), true);

       $getEmailCustomer = Customers::where('id',$infoTask["task"]["customer_id"])->first();

       $getEmailMember = TeamMember::where('id',$infoTask["task"]["tech_id"])->first();

       Mail::to($getEmailCustomer->email)->queue(new EmailNotify(($infoTask)));
       Mail::to($getEmailMember->email)->queue(new EmailNotify(($infoTask)));

       
    }
}
