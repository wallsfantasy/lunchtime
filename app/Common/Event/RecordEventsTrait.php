<?php

namespace App\Common\Event;

use App\Events\Event;

trait RecordEventsTrait
{
    /** @var Event[] */
    public $domainEvents = [];

    public function recordEvent(Event $event)
    {
        $this->domainEvents[] = $event;
    }
}
