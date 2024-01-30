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
    
    public function getNotificationTimes(): Array
    {
        $notificationInfo = [];
       
        if(Auth::user()->type_user == 1)
        {
            $teamMember = TeamMember::where('user_id',Auth::user()->id)->first();
           
            $servicesNotifications = CustomerNotifications::with('service')->with('customerLocation')
                ->whereHas('customer', function ($query) use($teamMember){
                   $query->Where('account_manager',$teamMember->id);
                  
                })
                ->where('treated',1)
                ->get();

        }
        else
        {
            $servicesNotifications = CustomerNotifications::where('treated',1)->with('service')->with('customer')->with('customerLocation')->get();
        }

        // dd($servicesNotifications->)

        foreach($servicesNotifications as $count => $notification)
        {
            $tm = TeamMember::where('id',$notification->customer->account_manager)->first();

            if($notification->customer_service_id == null){
                $member = $tm->name;
            } else {
                $personService = CustomerServices::where('id',$notification->customer_service_id)->first();
                $member = TeamMember::where('id',$personService->member_associated)->first();

                if($member != null)
                {
                    $member = $member->name;
                }
                else {
                    $member = $tm->name;
                }
                
            }

            $notificationInfo[$count] = ["customerServicesId" => $notification->id, "service" => $notification->service->name, "date_final_service" => $notification->end_service_date, "customer" => $notification->customer->short_name, "team_member" => $member, "customer_county" => $notification->customerLocation->description, "notification" => $notification->notification_day, "tratada" => $notification->treated];
        }

        return $notificationInfo;
    }

    public function changeTreatedStatus($idCustomerService): void
    {
        CustomerNotifications::where('id',$idCustomerService)->update(["treated" => "2"]);
    }
    

}
