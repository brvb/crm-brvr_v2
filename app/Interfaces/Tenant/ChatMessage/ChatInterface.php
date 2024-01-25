<?php

namespace App\Interfaces\Tenant\ChatMessage;

use App\Models\Tenant\Files;
use App\Models\Tenant\ChatMessage;
use Illuminate\Support\Collection;
use App\Models\Tenant\ChatMessageTechnical;

interface ChatInterface
{
    public function getMessages($id): Collection;

    public function messageSend($message,$user_id,$customer_id): ChatMessage;

    public function getMessagesBetweenTech($tech): Collection;

    public function messageSendBetweenTech($message,$user_id,$tech_id): ChatMessageTechnical;
   

}
