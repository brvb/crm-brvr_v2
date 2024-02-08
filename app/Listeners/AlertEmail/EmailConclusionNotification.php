<?php

namespace App\Listeners\AlertEmail;

use App\Models\User;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Config;
use App\Models\Tenant\Pedidos;
use App\Events\Alerts\AlertEvent;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use App\Mail\AlertEmail\AlertEmail;
use App\Models\Tenant\Intervencoes;
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
                    $tasksRed = Pedidos::
                    where('tech_id',$member->id)
                    ->where('prioridade',1)
                    ->where('estado','!=',5)
                    ->where('estado','!=',2)
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
    
                    /****************** */
    
                    /** Segundo Quadro **/
    
                    $otherTasks = Pedidos::where('tech_id',$member->id)
                    ->where('prioridade','!=',1)
                    ->where('estado','!=',5)
                    ->where('estado','!=',2)
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
    
                    /***************** */
    
                    /** Terceiro Quadro **/
    
                    $finishedTasksToday = Pedidos::
                    where('tech_id',$member->id)
                    ->where('estado',2)
                    ->whereHas('intervencoes', function ($query) {
                        $query->where('estado_pedido',2)->where('data_final',date('Y-m-d'));
                    })
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
    
                    /****************** */

                    /*** QUARTO QUADRO ***/

                    $finishedTimesToday =  Intervencoes::
                    where('user_id',$member->user_id)
                    ->where('data_final',date('Y-m-d'))
                    ->with('pedido')
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

                    $tasksRed = Pedidos::
                    where('tech_id',$dept->id)
                    ->where('prioridade',1)
                    ->where('estado','!=',5)
                    ->where('estado','!=',2)
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
    
                    /****************** */
    
                    /** Segundo Quadro **/
    
                    $otherTasks = Pedidos::where('tech_id',$dept->id)
                    ->where('prioridade','!=',1)
                    ->where('estado','!=',5)
                    ->where('estado','!=',2)
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
    
                    /***************** */
    
                    /** Terceiro Quadro **/
    
                    $finishedTasksToday = Pedidos::
                    where('tech_id',$dept->id)
                    ->where('estado',2)
                    ->whereHas('intervencoes', function ($query) {
                        $query->where('estado_pedido',2)->where('data_final',date('Y-m-d'));
                    })
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
    
                    /****************** */


                    /*** QUARTO QUADRO ***/

                    $finishedTimesToday =  Intervencoes::
                    where('user_id',$dept->user_id)
                    ->where('data_final',date('Y-m-d'))
                    ->with('pedido')
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


                 $tasksRed = Pedidos::
                    where('tech_id',$usr["teamMember_id"])
                    ->where('prioridade',1)
                    ->where('estado','!=',5)
                    ->where('estado','!=',2)
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
 
                 /****************** */
 
                 /** Segundo Quadro **/
 
                $otherTasks = Pedidos::where('tech_id',$usr["teamMember_id"])
                    ->where('prioridade','!=',1)
                    ->where('estado','!=',5)
                    ->where('estado','!=',2)
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
 
                 /***************** */
 
                 /** Terceiro Quadro **/
 
                 $finishedTasksToday = Pedidos::
                    where('tech_id',$usr["teamMember_id"])
                    ->where('estado',2)
                    ->whereHas('intervencoes', function ($query) {
                        $query->where('estado_pedido',2)->where('data_final',date('Y-m-d'));
                    })
                    ->with('prioridadeStat')
                    ->with('intervencoes')
                    ->with('servicesToDo')
                    ->with('customer')
                    ->with('tech')
                    ->orderBy('prioridade','ASC')
                    ->orderBy('tech_id','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();
 
                 /****************** */

                  /*** QUARTO QUADRO ***/

                $finishedTimesToday =  Intervencoes::
                    where('user_id',$teamMemberIndividual->user_id)
                    ->where('data_final',date('Y-m-d'))
                    ->with('pedido')
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
