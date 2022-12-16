<?php

namespace App\Listeners;

use App\Events\OnlineDriver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOnlineDriverNotification
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
     * @param  \App\Events\OnlineDriver  $event
     * @return void
     */
    public function handle(OnlineDriver $event)
    {
        //
    }
}
