<?php

namespace App\Listeners;

use App\Providers\MessageSending;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        //check get is from is set
        if (!empty($event->message->getFrom())) {

            //get current from array
            $from = $event->message->getFrom();

            //check sender already set by a in function code
            if (empty($event->message->getSender())) {

                //if empty set sender data as from data
                $event->message->setSender($from);
            }
        }
    }
}
