<?php

namespace App\Listeners\AlertEmail;

use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Events\Alerts\EmailConclusionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Tenant\CustomerNotifications;
use App\Mail\AlertEmail\AlertEmailConclusionDay;
use App\Models\Tenant\TeamMember as TenantTeamMember;
use App\Models\User;

class EmailConclusionNotification
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

   
    public function handle(EmailConclusionEvent $conclusionEvent)
    {
 
        $eventUsers = $conclusionEvent->users;

        foreach($eventUsers as $email => $usr)
        {
            $infoSendEmail = [];

            if($usr["hierarquia"] == "1")
            {
               $teamMembers = TeamMember::where('id_hierarquia','!=',1)->get();
                  /** Primeiro Quadro **/
                
               foreach($teamMembers as $member)
               {
                    $tasksRed = Tasks::
                    where('tech_id',$member->id)
                    ->where('prioridade',1)
                    ->whereHas('taskReports', function ($query) {
                        $query->where('reportStatus','!=',2);
                    })
                    ->with('tasksTimes')
                    ->with('prioridades')
                    ->with('servicesToDo')
                    ->with('taskCustomer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('preview_date','ASC')
                    ->get();
    
                    /****************** */
    
                    /** Segundo Quadro **/
    
                    $otherTasks = Tasks::where('tech_id',$member->id)
                    ->where('prioridade','!=',1)
                    ->whereHas('taskReports', function ($query) {
                        $query->where('reportStatus','!=',2);
                    })
                    ->with('tasksTimes')
                    ->with('prioridades')
                    ->with('servicesToDo')
                    ->with('taskCustomer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('preview_date','ASC')
                    ->get();
    
                    /***************** */
    
                    /** Terceiro Quadro **/
    
                    $finishedTasksToday = Tasks::
                    where('tech_id',$member->id)
                    ->whereHas('taskReports', function ($query) {
                        $query->where('reportStatus',2)->where('end_date',date('Y-m-d'));
    
                    })
                    ->with('tasksTimes')
                    ->with('prioridades')
                    ->with('servicesToDo')
                    ->with('taskCustomer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('preview_date','ASC')
                    ->get();
    
                    /****************** */

                    /*** QUARTO QUADRO ***/

                  $finishedTimesToday =  TasksTimes::whereHas('tasksReports')
                  ->with('tasksReports')
                  ->with('service')
                  ->where('tech_id',$member->user_id)
                  ->where('date_end',date('Y-m-d'))
                  ->get();   

                  /******************** */

                    $infoSendEmail = [
                        "nome" => $member->name,
                        "primeiro_quadro" => $tasksRed,
                        "segundo_quadro" => $otherTasks,
                        "terceiro_quadro" => $finishedTasksToday,
                        "quarto_quadro" => $finishedTimesToday
                    ];

                    Mail::to($email)->queue(new AlertEmailConclusionDay(($infoSendEmail)));

               }

            }
            else if($usr["hierarquia"] == "2")
            {
                $teamMembers = TeamMember::where('id',$usr["teamMember_id"])->first();
            
                $seu_departamento = TeamMember::where('id_departamento',$teamMembers->id_departamento)->get();

                foreach($seu_departamento as $dept)
                {
                    /** Primeiro Quadro **/

                    $tasksRed = Tasks::
                    where('tech_id',$dept->id)
                    ->where('prioridade',1)
                    ->whereHas('taskReports', function ($query) {
                        $query->where('reportStatus','!=',2);
                    })
                    ->with('tasksTimes')
                    ->with('prioridades')
                    ->with('servicesToDo')
                    ->with('taskCustomer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('preview_date','ASC')
                    ->get();
    
                    /****************** */
    
                    /** Segundo Quadro **/
    
                    $otherTasks = Tasks::where('tech_id',$dept->id)
                    ->where('prioridade','!=',1)
                    ->whereHas('taskReports', function ($query) {
                        $query->where('reportStatus','!=',2);
                    })
                    ->with('tasksTimes')
                    ->with('prioridades')
                    ->with('servicesToDo')
                    ->with('taskCustomer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('preview_date','ASC')
                    ->get();
    
                    /***************** */
    
                    /** Terceiro Quadro **/
    
                    $finishedTasksToday = Tasks::
                    where('tech_id',$dept->id)
                    ->whereHas('taskReports', function ($query) {
                        $query->where('reportStatus',2)->where('end_date',date('Y-m-d'));
    
                    })
                    ->with('tasksTimes')
                    ->with('prioridades')
                    ->with('servicesToDo')
                    ->with('taskCustomer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('preview_date','ASC')
                    ->get();
    
                    /****************** */


                    /*** QUARTO QUADRO ***/

                    $finishedTimesToday =  TasksTimes::whereHas('tasksReports')
                    ->with('tasksReports')
                    ->with('service')
                    ->where('tech_id',$dept->user_id)
                    ->where('date_end',date('Y-m-d'))
                    ->get();   

                    /******************** */

                    $infoSendEmail = [
                        "nome" => $dept->name,
                        "primeiro_quadro" => $tasksRed,
                        "segundo_quadro" => $otherTasks,
                        "terceiro_quadro" => $finishedTasksToday,
                        "quarto_quadro" => $finishedTimesToday
                    ];

                    Mail::to($teamMembers->email)->queue(new AlertEmailConclusionDay(($infoSendEmail)));
                }
            }
            else if($usr["hierarquia"] == "3")
            {
                $teamMemberIndividual = TeamMember::where('id',$usr["teamMember_id"])->first();

                 /** Primeiro Quadro **/


                 $tasksRed = Tasks::
                 where('tech_id',$usr["teamMember_id"])
                 ->where('prioridade',1)
                 ->whereHas('taskReports', function ($query) {
                     $query->where('reportStatus','!=',2);
                 })
                 ->with('tasksTimes')
                 ->with('prioridades')
                 ->with('servicesToDo')
                 ->with('taskCustomer')
                 ->with('tech')
                 ->orderBy('prioridade','ASC')
                 ->orderBy('tech_id','ASC')
                 ->orderBy('preview_date','ASC')
                 ->get();
 
                 /****************** */
 
                 /** Segundo Quadro **/
 
                 $otherTasks = Tasks::where('tech_id',$usr["teamMember_id"])
                 ->where('prioridade','!=',1)
                 ->whereHas('taskReports', function ($query) {
                     $query->where('reportStatus','!=',2);
                 })
                 ->with('tasksTimes')
                 ->with('prioridades')
                 ->with('servicesToDo')
                 ->with('taskCustomer')
                 ->with('tech')
                 ->orderBy('prioridade','ASC')
                 ->orderBy('tech_id','ASC')
                 ->orderBy('preview_date','ASC')
                 ->get();
 
                 /***************** */
 
                 /** Terceiro Quadro **/
 
                 $finishedTasksToday = Tasks::
                 where('tech_id',$usr["teamMember_id"])
                 ->whereHas('taskReports', function ($query) {
                    $query->where('reportStatus',2)->where('end_date',date('Y-m-d'));
 
                 })
                 ->with('tasksTimes')
                 ->with('prioridades')
                 ->with('servicesToDo')
                 ->with('taskCustomer')
                 ->with('tech')
                 ->orderBy('prioridade','ASC')
                 ->orderBy('tech_id','ASC')
                 ->orderBy('preview_date','ASC')
                 ->get();
 
                 /****************** */

                  /*** QUARTO QUADRO ***/

                  $finishedTimesToday =  TasksTimes::whereHas('tasksReports')
                  ->with('tasksReports')
                  ->with('service')
                  ->where('tech_id',$teamMemberIndividual->user_id)
                  ->where('date_end',date('Y-m-d'))
                  ->get();   

                  /******************** */

                 $infoSendEmail = [
                     "nome" => $teamMemberIndividual->name,
                     "primeiro_quadro" => $tasksRed,
                     "segundo_quadro" => $otherTasks,
                     "terceiro_quadro" => $finishedTasksToday,
                     "quarto_quadro" => $finishedTimesToday
                 ];

                 Mail::to($email)->queue(new AlertEmailConclusionDay(($infoSendEmail)));
            }

        }
              
       
        //Mail::to($email->email)->queue(new AlertEmail(($alert)));

              
    }
}
