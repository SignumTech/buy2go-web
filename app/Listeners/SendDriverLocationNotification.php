<?php

namespace App\Listeners;

use App\Events\DriverLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDriverLocationNotification
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
     * @param  \App\Events\DriverLocation  $event
     * @return void
     */
    public function handle(DriverLocation $event)
    {
        //
    }
}
