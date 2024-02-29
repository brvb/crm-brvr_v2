<?php

namespace App\Listeners\Tasks;

use Exception;
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
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Notifications\Tasks\TasksDispatchedNotification;

class SendPDFNotification
{
    use Notifiable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    protected object $customerRepository;
    public function __construct(CustomersInterface $interfaceCustomers)
    {
        $this->customerRepository = $interfaceCustomers;
    }

     
    public function handle(SendPDF $valuesInfo)
    {   
        $customer = $this->customerRepository->getSpecificCustomerInfo($valuesInfo->valuesInfo->customer_id);
        
        try {
            if($customer->customers->email != "")
            {
                $array = explode(";",$customer->customers->email);
        
                foreach($array as $email)
                {
                    //CLIENTE
                    Mail::to($email)->queue(new PDFEmail($valuesInfo->valuesInfo,$valuesInfo->pdf));
                }
            }
            
        }
        catch (Exception $e) {
            echo $e;
        }
      
        $tm = TeamMember::where('id',$valuesInfo->valuesInfo->tech_id)->first();
        Mail::to($tm->email)->queue(new PDFEmail($valuesInfo->valuesInfo,$valuesInfo->pdf));

        
    }
}
