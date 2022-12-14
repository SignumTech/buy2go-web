<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Events\DriverAssigned;
use App\Listeners\DriverAssignedNotification;
use App\Events\VisitAssigned;
use App\Listeners\SendVisitAssignedNotification;
use App\Events\VisitLocation;
use App\Listeners\SendVisitLocationNotification;
use App\Events\DriverLocation;
use App\Listeners\SendDriverLocationNotification;
use App\Events\DriverRejectedOrder;
use App\Events\CashWithdrawn;
use App\Listeners\DriverRejectedOrderNotification;
use App\Listeners\CashWithdrawnNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        DriverAssigned::class => [
            DriverAssignedNotification::class,
        ],
        DriverRejectedOrder::class => [
            DriverRejectedOrderNotification::class,
        ],
        CashWithdrawn::class => [
            CashWithdrawnNotification::class,
        ],
        VisitAssigned::class => [
            SendVisitAssignedNotification::class,
        ],
        VisitLocation::class => [
            SendVisitLocationNotification::class,
        ],
        DriverLocation::class => [
            SendDriverLocationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
