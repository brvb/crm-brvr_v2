<?php

namespace App\Http\Livewire\Tenant\ChatUsers;


use Carbon\Carbon;
use App\Models\User;

use Livewire\Component;
use App\Events\ChatMessage;
use App\Events\LoginStatus;
use App\Models\Tenant\Files;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Shetabit\Visitor\Visitor;
use App\Events\ChatMessageAdmin;
use App\Models\Tenant\Customers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Events\ChatEmail\ChatEmailEvent;
use App\Events\ChatMessage as EventsChatMessage;
use App\Interfaces\Tenant\ChatMessage\ChatInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;

class ChatUsers extends Component
{
    
    protected $listeners = ["messagesRightSide" => "messagesRightSide", "chatUpdate" => "chatUpdate"];

    public int $tech = 0;

    private ?object $chat = NULL;

    protected object $chatRepository;

    protected object $alertRepository;

    public string $usermsg = '';


    public function boot(ChatInterface $interfaceChat,AlertMessageInterface $interfaceAlert)
    {
        $this->chatRepository = $interfaceChat;
        $this->alertRepository = $interfaceAlert;
    }

    // public function mount(): void
    // {
        
    //     $this->chat = null;
             
    // }

    public function messagesRightSide($tech)
    {
        // $this->dispatchBrowserEvent("cleanChatBox"); 
        $this->tech = $tech;
        $this->chat = $this->chatRepository->getMessagesBetweenTech($this->tech);
        $this->dispatchBrowserEvent("refreshChatPosition"); 

    }

    public function teste()
    {
        $this->dispatchBrowserEvent("refreshChatPosition"); 
    }

    public function chatUpdate()
    {
        $this->chat = $this->chatRepository->getMessagesBetweenTech($this->tech);
        $this->dispatchBrowserEvent("refreshChatPosition");   
    }

    

    public function SendMessage()
    {
        if($this->usermsg != "")
        {
            $this->chatRepository->messageSendBetweenTech($this->usermsg,Auth::user()->id,$this->tech);
            $this->usermsg = '';
            $this->chat = $this->chatRepository->getMessagesBetweenTech($this->tech);
            $this->alertRepository->SendNotificationBetweenTech(Auth::user()->id,$this->tech,"message");
            event(new ChatMessage());

            //verificação se estiver logado ou nao
           
        }
    }

   
    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {  
        return view('tenant.livewire.chatuser.chatuser', [
             "chat" => $this->chat,
             "tech" => $this->tech
        ]);
    }
}
