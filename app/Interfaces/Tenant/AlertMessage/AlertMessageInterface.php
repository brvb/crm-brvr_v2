<?php

namespace App\Interfaces\Tenant\AlertMessage;

use App\Models\Tenant\Files;
use Illuminate\Support\Collection;
use App\Models\Tenant\Notifications;

interface AlertMessageInterface
{
    public function getNotifications($receiver_user): Collection;

    public function updateReadState($receiver_id): int;

    public function SendNotification($idSender,$customerId,$type): Notifications;

    public function SendNotificationBetweenTech($idSender,$techId,$type): Notifications;
 
}
