<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lat;
    public $lng;
    public $assignment;
    public $driver_id;
    public $name;
    public $afterCommit = true;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($lat,$lng,$assignment,$driver_id,$name)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->assignment = $assignment;
        $this->driver_id = $driver_id;
        $this->name = $name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('driver_location.0');
    }
}
