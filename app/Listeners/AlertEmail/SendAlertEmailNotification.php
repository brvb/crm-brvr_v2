<?php

namespace App\Listeners\AlertEmail;

use App\Models\Tenant\Config;
use App\Events\Alerts\AlertEvent;
use App\Mail\TeamMember\TeamMember;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Mail\AlertEmail\AlertEmail;
use App\Models\Tenant\CustomerNotifications;
use App\Models\Tenant\CustomerServices;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAlertEmailNotification
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

   
    public function handle(AlertEvent $alertEvent)
    {
        $alert = $alertEvent->customerService;
        $emailConfig = Config::first();
        $countTimes = 0;

       // $notification_day = date('Y-m-d', strtotime('-'.$alert->alert.' day', strtotime($alert->end_date)));
        $notification_day = date('Y-m-d');

        if(json_decode($emailConfig->alert_email) != null)
        {
            foreach(json_decode($emailConfig->alert_email) as $email)
            {
                Mail::to($email->email)->queue(new AlertEmail(($alert)));

                if($countTimes == 0)
                {
                    CustomerNotifications::create([
                        'service_id' => $alert->service_id,
                        'end_service_date' => $alert->end_date,
                        'customer_id' => $alert->customer_id,
                        'location_id' => $alert->location_id,
                        'notification_day' => $notification_day,
                        'treated' => "1",
                        'customer_service_id' => $alert->id
                    ]);
                }
                $countTimes++;
            }
        }
        //Mail::to($emailConfig->alert_email)->queue(new AlertEmail($alert));
    }
}
