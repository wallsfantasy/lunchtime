<?php

namespace App\Providers;

use App\Model\Cycle\Event\MemberLeftCycleEvent;
use App\Model\Cycle\EventListener\CloseCycleNoMemberListener;
use App\Model\Cycle\Projection\CycleProjector;
use App\Model\Cycle\Projection\UserProjector;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'App\Events\Event' => [
//            'App\Listeners\EventListener',
//        ],
        MemberLeftCycleEvent::class => [
            CloseCycleNoMemberListener::class,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected $subscribe = [
        UserProjector::class,
        CycleProjector::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
