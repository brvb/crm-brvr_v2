<?php

namespace App\Listeners\Tasks;

use App\Models\User;
use App\Mail\Tasks\PDFEmail;
use App\Models\Tenant\Tasks;
use App\Events\Tasks\SendPDF;
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

class SendPDFNotification
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

     
    public function handle(SendPDF $valuesInfo)
    {   
      
        Mail::to($valuesInfo->valuesInfo->customer->email)->queue(new PDFEmail($valuesInfo->valuesInfo,$valuesInfo->pdf));

        $tm = TeamMember::where('id',$valuesInfo->valuesInfo->tech_id)->first();
        Mail::to($tm->email)->queue(new PDFEmail($valuesInfo->valuesInfo,$valuesInfo->pdf));

        
    }
}
