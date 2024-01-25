<?php

namespace App\Http\Livewire\Tenant\AnalysisDashboard;

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

    private ?object $arrayTimes;

    // public function boot(TeamMemberInterface $members, CustomerNotificationInterface $customerNotification, TasksInterface $taskInterface)
    // {
    //     $this->TeamMember = $members;
    //     $this->customerNotification = $customerNotification;
    //     $this->taskInterface = $taskInterface;
    // }

    public function mount()
    {
           
    }


    public function render()
    {
       
        return view('tenant.livewire.analysisdashboard.show');
    }
}
