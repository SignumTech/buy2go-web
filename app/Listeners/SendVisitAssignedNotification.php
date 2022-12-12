<?php

namespace App\Listeners;

use App\Events\VisitAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVisitAssignedNotification
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
     * @param  \App\Events\VisitAssigned  $event
     * @return void
     */
    public function handle(VisitAssigned $event)
    {
        //
    }
}
