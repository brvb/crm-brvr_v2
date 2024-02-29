<?php

namespace App\Listeners\AlertEmail;

use App\Models\Tenant\Config;
use App\Events\Alerts\AlertEvent;
use App\Mail\AlertEmail\AlertEmail;
use App\Mail\TeamMember\TeamMember;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tasks\TaskReportFinished;
use App\Models\Tenant\CustomerServices;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TeamMember\TeamMemberEvent;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Tenant\CustomerNotifications;
use App\Interfaces\Tenant\Customers\CustomersInterface;

class SendAlertEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected object $customerRepository;
    protected object $customerLocationRepository;
    public function __construct(CustomersInterface $interfaceCustomers, CustomerLocationsInterface $customerLocation)
    {
        $this->customerRepository = $interfaceCustomers;
        $this->customerLocationRepository = $customerLocation;
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
                $customer = $this->customerRepository->getSpecificCustomerInfo($alert->customer_id);

                $customerLocation = $this->customerLocationRepository->getSpecificLocationInfo($alert->location_id);

                Mail::to($email->email)->queue(new AlertEmail($alert,$customer,$customerLocation));

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
