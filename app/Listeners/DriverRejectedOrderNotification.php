<?php

namespace App\Listeners;

use App\Events\DriverRejectedOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DriverRejectedOrderNotification
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
     * @param  \App\Events\DriverRejectedOrder  $event
     * @return void
     */
    public function handle(DriverRejectedOrder $event)
    {
        //
    }
}
