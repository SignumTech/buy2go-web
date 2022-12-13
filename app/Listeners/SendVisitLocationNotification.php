<?php

namespace App\Listeners;

use App\Events\VisitLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVisitLocationNotification
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
     * @param  \App\Events\VisitLocation  $event
     * @return void
     */
    public function handle(VisitLocation $event)
    {
        //
    }
}
