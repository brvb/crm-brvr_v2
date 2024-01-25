<?php

namespace App\Repositories\Tenant\ChatMessage;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\Files;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use App\Models\Tenant\ChatMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use App\Models\Tenant\ChatMessageTechnical;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Tenant\Files\FilesInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\ChatMessage\ChatInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;


class ChatMessageRepository implements ChatInterface
{
    public function getMessages($id): Collection
    {
        $chat = ChatMessage::where('customer_id',$id)->get();

        return $chat;
    }

    public function getMessagesBetweenTech($tech): Collection
    {
        $chat = ChatMessageTechnical::where(function($query) use ($tech){
            $query->where('user_id',Auth::user()->id)
                   ->where('tech_id',$tech);
        })
        ->orWhere(function($query) use ($tech){
            $query->where('user_id',$tech)
            ->where('tech_id',Auth::user()->id);
        })
       ->get();


        return $chat;
    }

    public function messageSend($message,$user_id,$customer_id): ChatMessage
    {
        return DB::transaction(function () use ($message,$user_id,$customer_id) {
            $chat = ChatMessage::create([
                "message" => $message,
                "user_id" => $user_id,
                "customer_id" => $customer_id
            ]);

            return $chat;
        });
    }

    public function messageSendBetweenTech($message,$user_id,$tech_id): ChatMessageTechnical
    {
        return DB::transaction(function () use ($message,$user_id,$tech_id) {
            $chat = ChatMessageTechnical::create([
                "message" => $message,
                "user_id" => $user_id,
                "tech_id" => $tech_id
            ]);

            return $chat;
        });
    }
}

