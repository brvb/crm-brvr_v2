<?php

namespace App\Http\Livewire\Tenant\OpenTimes;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Tenant\Tasks;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TasksReports;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Tasks\TasksInterface;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\CustomerServices\CustomerServicesInterface;
use App\Interfaces\Tenant\CustomerNotification\CustomerNotificationInterface;

class Show extends Component
{
    
    //protected $listeners = ["CalendarNextChanges" => 'CalendarNextChanges', "CalendarPreviousChanges" => 'CalendarPreviousChanges'];
   
    // protected TeamMemberInterface $TeamMember;
    // protected CustomerNotificationInterface $customerNotification;
    // protected TasksInterface $taskInterface;

    private ?array $arrayTimes;

    // public function boot(TeamMemberInterface $members, CustomerNotificationInterface $customerNotification, TasksInterface $taskInterface)
    // {
    //     $this->TeamMember = $members;
    //     $this->customerNotification = $customerNotification;
    //     $this->taskInterface = $taskInterface;
    // }

    public function mount()
    {

        $users = User::all();

        $taskTimes = TasksTimes::with('service')->with('task')->orderBy('id','asc')->where('date_end',null)->get();

        $arrayTimes = [];

        foreach($users as $i => $user)
        {
            foreach($taskTimes as $count => $time)
            {
                if($user->id == $time->tech_id)
                {
                    $serviceName = $time->service->name;

                    $taskReference = $time->task->reference;

                    $customer_id = $time->task->customer_id;

                    $customer_name = Customers::where('id',$customer_id)->first();

                    $timeReport = TasksReports::where('task_id',$time->task_id)->first();

                    $arrayTimes[$user->name] = [
                        "photo" => $user->photo,
                        "service" => $serviceName,
                        "reference" => $taskReference,
                        "customer" => $customer_name->name,
                        "date_begin" => $time->date_begin,
                        "hour_begin" => $time->hour_begin,
                        "task_id" => $timeReport->id,
                        "tech" => $time->tech_id
                    ];
                }
            }
        }

        $this->arrayTimes = $arrayTimes;
    }


    public function render()
    {
        if($this->arrayTimes == null)
        {
            $this->arrayTimes = [];
        }

        return view('tenant.livewire.opentimes.show',["arrayTimes" => $this->arrayTimes]);
    }
}
