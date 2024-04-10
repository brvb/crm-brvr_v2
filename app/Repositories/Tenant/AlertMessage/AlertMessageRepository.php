<?php

namespace App\Repositories\Tenant\AlertMessage;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\Files;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use App\Models\Tenant\CustomerNotifications;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Tenant\Files\FilesInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;
use App\Interfaces\Tenant\AlertMessage\AlertMessageInterface;

class AlertMessageRepository implements AlertMessageInterface
{

    public function getNotifications($receiver_user): Collection
    {
      
      if(Auth::user()->type_user == 0){

         $notifications = Notifications::
         select('notifications.group_chat', DB::raw("MAX(created_at) as created_at"),DB::raw("MAX(notifications.receiver_user_id) as receiver_user_id"),DB::raw("MAX(notifications.sender_user_id) as sender_user_id"),DB::raw("MAX(notifications.type) as type"))
         ->where('notifications.receiver_user_id',$receiver_user)
         ->where('notifications.read',0)
         ->with('senderUser')
         ->with('receivedUser')
         ->groupBy('notifications.group_chat')
         ->get();
       
      }
      else {
         $notifications = Notifications::where('receiver_user_id',$receiver_user)->where('read',0)->with('senderUser')->with('receivedUser')->get();
      }
      
      
      return $notifications;
    }

    public function updateReadState($id_receiver): int
    {

      // return DB::transaction(function () use ($id_receiver) {
         
      //  $notification =  Notifications::where('receiver_user_id',$id_receiver)->update([
      //       "read" => 1
      //    ]);

      //    return $notification;
      // });

      return DB::transaction(function () use ($id_receiver) {
         
         $notification =  Notifications::where('receiver_user_id',$id_receiver)->update([
            "read" => 1
         ]);
         
         if(Auth::user()->type_user == 0){
            //se for admin apaga logo tudo
            CustomerNotifications::where('treated',1)->update([
               "treated" => 2
            ]);
         }
         else {
            $CustomerServices = CustomerNotifications::where('treated',1)->get();
         
            foreach ($CustomerServices as $servic){
               $services = CustomerServices::where('id',$servic->customer_service_id)->first();
              
               $team = TeamMember::where('id',$services->member_associated)->first();

               if($team->user_id == $id_receiver){
              
                  CustomerNotifications::where('id',$servic->id)->update([
                     "treated" => 2
                  ]);

               }

            }

      
         }
        
    
         return $notification;
      });
     
    }

    public function SendNotification($idSender,$customerId,$type): Notifications
    {
        return DB::transaction(function () use ($idSender,$customerId,$type) {
                 
          //Mensagem foi enviada pelo cliente, preciso do membro e do admin
           if(Auth::user()->type_user == 2){
              $customer = Customers::where('id',$customerId)->first();
              $memberOfThisCustomer = TeamMember::where('id',$customer->account_manager)->first();
              $membersToReceive = User::where('id',$memberOfThisCustomer->user_id)->orWhere('type_user',0)->get();
           }
           //Mensagem foi enviada pelo membro, preciso do cliente e do admin
           else if(Auth::user()->type_user == 1){
             $memberThatSend = TeamMember::where('user_id',$idSender)->first();
             $customer = Customers::where('account_manager',$memberThatSend->id)->where('id',$customerId)->first();
             $membersToReceive = User::where('id',$customer->user_id)->orWhere('type_user',0)->get();
           }
           //Mensagem foi enviada pelo admin, preciso do cliente e do membro
           else {
              $customer = Customers::where('id',$customerId)->first();
              $member = TeamMember::where('id',$customer->account_manager)->first();
              $membersToReceive = User::where('id',$customer->user_id)->orWhere('id',$member->user_id)->get();
           }
          
           foreach($membersToReceive as $member)
           {
              $notification = Notifications::create([
                 "sender_user_id" => $idSender,
                 "receiver_user_id" => $member->id,
                 "read" => 0,
                 "type" => $type,
                 "group_chat" => $customerId
              ]);
           }
          
           return $notification;

        });
    }

    public function SendNotificationBetweenTech($idSender,$techId,$type): Notifications
    {
      return DB::transaction(function () use ($idSender,$techId,$type) {

         $notification = Notifications::create([
            "sender_user_id" => $idSender,
            "receiver_user_id" => $techId,
            "read" => 0,
            "type" => $type,
            "group_chat" => null
         ]);

         return $notification;
      });
    }

      

}

