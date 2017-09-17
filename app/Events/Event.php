<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\Uuid;

abstract class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array */
    public $meta = [];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    abstract public function getEventName();

    /**
     * @param null|string $correlationId
     * @param null|string $causationId
     */
    public function addMeta(string $correlationId = null, string $causationId = null)
    {
        $hostName = gethostname();
        $this->meta['host_name'] = $hostName;
        $this->meta['host_ip'] = gethostbyname($hostName);
        $this->meta['dispatched_at'] = \DateTime::createFromFormat('U.u', microtime(true))
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s.u');
        $this->meta['message_id'] = Uuid::uuid4()->toString();
        $this->meta['message_name'] = $this->getEventName();
        $this->meta['message_type'] = 'event';
        $this->meta['correlation_id'] = $correlationId;
        $this->meta['causation_id'] = $causationId;
    }
}
