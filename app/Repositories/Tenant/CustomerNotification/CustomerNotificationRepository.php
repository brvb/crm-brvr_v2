<?php

namespace App\Repositories\Tenant\CustomerNotification;

use App\Models\Tenant\Customers;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\CustomerNotifications;
use App\Interfaces\Tenant\CustomerNotification\CustomerNotificationInterface;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\TeamMember;

class CustomerNotificationRepository implements CustomerNotificationInterface
{
    
    public function getNotificationTimes($customersRepository,$customerLocationRepository): Array
    {
        $notificationInfo = [];
       
        if(Auth::user()->type_user == 1)
        {
            $teamMember = TeamMember::where('user_id',Auth::user()->id)->first();
           
            // $servicesNotifications = CustomerNotifications::with('service')->with('customerLocation')
            //     ->whereHas('customer', function ($query) use($teamMember){
            //        $query->Where('account_manager',$teamMember->id);
                  
            //     })
            //     ->where('treated',1)
            //     ->get();

            $servicesNotifications = CustomerNotifications::where('treated',1)->with('service')->with('customer')->with('customerLocation')->get();

        }
        else
        {
            $servicesNotifications = CustomerNotifications::where('treated',1)->with('service')->with('customer')->with('customerLocation')->get();
        }

      

        foreach($servicesNotifications as $count => $notification)
        {
            // $tm = TeamMember::where('id',$notification->customer->account_manager)->first();

            // if($notification->customer_service_id == null){
            //     $member = $tm->name;
            // } else {
            //     $personService = CustomerServices::where('id',$notification->customer_service_id)->first();
            //     $member = TeamMember::where('id',$personService->member_associated)->first();

            //     if($member != null)
            //     {
            //         $member = $member->name;
            //     }
            //     else {
            //         $member = $tm->name;
            //     }
                
            // }

            $personService = CustomerServices::where('id',$notification->customer_service_id)->first();
            $member = TeamMember::where('id',$personService->member_associated)->first();
        
            $customer = $customersRepository->getSpecificCustomerInfo($personService->customer_id);
            $location = $customerLocationRepository->getSpecificLocationInfo($personService->location_id); 


            $notificationInfo[$count] = ["customerServicesId" => $notification->id, "service" => $notification->service->name, "date_final_service" => $notification->end_service_date, "customer" => $customer->customers->name, "team_member" => $member->name, "customer_county" => $location->locations->address, "notification" => $notification->notification_day, "tratada" => $notification->treated];
        }

        return $notificationInfo;
    }

    public function changeTreatedStatus($idCustomerService): void
    {
        CustomerNotifications::where('id',$idCustomerService)->update(["treated" => "2"]);
    }
    

}
