<?php

namespace App\Listeners;

use App\Events\ConfirmPickup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendConfirmPickupNotification
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
     * @param  \App\Events\ConfirmPickup  $event
     * @return void
     */
    public function handle(ConfirmPickup $event)
    {
        //
    }
}
