<?php

namespace App\Model\Cycle\Event\Job;

use App\Common\Client\NewsFeed\FakeNewsFeedClient;
use App\Model\Cycle\Event\UserJoinedCycleEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostJoinCycleMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var UserJoinedCycleEvent */
    private $event;

    public function __construct(UserJoinedCycleEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FakeNewsFeedClient $client)
    {
        $message = "I joined lunch group {$this->event->cycleName} at lunchtime.com";

        $client->postMessage($this->event->userId, $message);
    }
}
