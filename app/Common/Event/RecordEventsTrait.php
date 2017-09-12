<?php

namespace App\Common\Event;

trait RecordEventsTrait
{
    public $domainEvents = [];

    public function recordEvent($event)
    {
        $this->domainEvents[] = $event;
    }

    public function getEvents(): array
    {
        return $this->domainEvents;
    }
}
