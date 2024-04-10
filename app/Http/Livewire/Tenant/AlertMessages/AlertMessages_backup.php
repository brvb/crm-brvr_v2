<?php

namespace App\Http\Livewire\Tenant\AlertMessages;

use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;
use Livewire\Component;
use App\Models\Tenant\Files;
use Livewire\WithPagination;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Tenant\Customers\CustomersInterface;


class AlertMessages extends Component
{
    use WithPagination;

    protected $listeners = ["AlertMessages" => "AlertMessages","AfterPageRefresh"];

    private object $notifications;
   

    protected object $alertMessageRepository;

    public function boot(AlertMessageInterface $interfaceAlert)
    {
        $this->alertMessageRepository = $interfaceAlert;
    }

    public function mount(): void
    {
       $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);  
       
    }

    //vem do livewire emit do evento do pusher
    public function AlertMessages()
    {
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);

        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }
        $this->dispatchBrowserEvent("checkRead",["read" => $read]);
    }

    public function AfterPageRefresh()
    {
        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);

        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }
        $this->dispatchBrowserEvent("checkRead",["read" => $read]);
    }

    
    public function markRead()
    {

        //dar update e colocar como read a 1
        $this->alertMessageRepository->updateReadState(Auth::user()->id);

        $this->notifications = $this->alertMessageRepository->getNotifications(Auth::user()->id);
        $read = 1;
        if($this->notifications->count() == 0)
        {
           //as notificações estão todas lidas
           $read = 0;
        }
        $this->dispatchBrowserEvent("checkRead",["read" => $read]);

    }


    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {
        return view('tenant.livewire.alertmessages.show', [
            'notifications' => $this->notifications
        ]);
    }
}
