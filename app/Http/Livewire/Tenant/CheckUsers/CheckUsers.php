<?php

namespace App\Http\Livewire\Tenant\CheckUsers;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Tenant\Files;
use Livewire\WithPagination;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;


class CheckUsers extends Component
{
    // use WithPagination;

     protected $listeners = ["RefreshUserLog" => "RefreshUserLog"];

    private array $users;
   

    //protected object $alertMessageRepository;

    // public function boot(AlertMessageInterface $interfaceAlert)
    // {
    //     $this->alertMessageRepository = $interfaceAlert;
    // }

    public function mount(): void
    {
        $users = User::where('id',"!=",Auth::user()->id)->orderBy('name','asc')->get();

        $peopleByName = [];
        $groupedByName = [];

        //extrai a primeira letra de todos
        foreach($users as $i => $user)
        {
            $peopleByName[$i] = mb_substr($user->name,0,1);
        }

        //remove letras repetidas
        $removeRepeated = array_unique($peopleByName);
       
       
        foreach($users as $i => $user)
        {
            if($user->last_seen != "")
            {
                $startDate = Carbon::parse($user->last_seen);
                $endDate = Carbon::parse(date('Y-m-d H:i:s'));
    
                $diff = $startDate->diff($endDate);
    
                $numberMinutes = $diff->days * 24 * 60;
                $numberMinutes += $diff->h * 60;
                $numberMinutes += $diff->i; 
    
                if($numberMinutes == 0 || $startDate == $endDate || $numberMinutes <= 30)
                {
                    $checkStatus = "online";
                }
                else
                {
                    $checkStatus = "offline";
                }
            }
            else {
                $checkStatus = "offline";
            }
          

            foreach($removeRepeated as $key => $letter)
            {
                if(mb_substr($user->name,0,1) == $letter)
                {
                    $groupedByName[$letter][$i] = ["id" => $user->id , "type_user" => $user->type_user, "name" => $user->name, "last_seen" => $user->last_seen, "photo" => $user->photo, "status" => $checkStatus];
                }
                
            }
        }
        
        $this->users = $groupedByName;

    }

   
    public function RefreshUserLog()
    {
        $users = User::where('id',"!=",Auth::user()->id)->orderBy('name','asc')->get();

        $peopleByName = [];
        $groupedByName = [];

        //extrai a primeira letra de todos
        foreach($users as $i => $user)
        {
            $peopleByName[$i] = mb_substr($user->name,0,1);
        }

        //remove letras repetidas
        $removeRepeated = array_unique($peopleByName);
       
       
        foreach($users as $i => $user)
        {
            if($user->last_seen != "")
            {
                $startDate = Carbon::parse($user->last_seen);
                $endDate = Carbon::parse(date('Y-m-d H:i:s'));
    
                $diff = $startDate->diff($endDate);
    
                $numberMinutes = $diff->days * 24 * 60;
                $numberMinutes += $diff->h * 60;
                $numberMinutes += $diff->i; 
    
                if($numberMinutes == 0 || $startDate == $endDate || $numberMinutes <= 30)
                {
                    $checkStatus = "online";
                }
                else
                {
                    $checkStatus = "offline";
                }
            }
            else {
                $checkStatus = "offline";
            }
          

            foreach($removeRepeated as $key => $letter)
            {
                if(mb_substr($user->name,0,1) == $letter)
                {
                    $groupedByName[$letter][$i] = ["id" => $user->id, "type_user" => $user->type_user, "name" => $user->name, "last_seen" => $user->last_seen, "photo" => $user->photo, "status" => $checkStatus];
                }
                
            }
        }
        
        $this->users = $groupedByName;
    }

    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {
        $usr = $this->users;
        return view('tenant.livewire.checkusers.show',["users" => $usr]);
    }
}
