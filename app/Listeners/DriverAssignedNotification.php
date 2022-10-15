<?php

namespace App\Listeners;

use App\Events\DriverAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DriverAssignedNotification
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
     * @param  \App\Events\DriverAssigned  $event
     * @return void
     */
    public function handle(DriverAssigned $event)
    {
        //
    }
}
