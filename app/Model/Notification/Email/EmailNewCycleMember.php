<?php

namespace App\Model\Notification\Email;

use App\Common\Mailer\FakeMailer;
use App\Model\Cycle\CycleRepository;
use App\Model\Cycle\Event\UserJoinedCycleEvent;
use App\Model\Cycle\Member;
use App\Model\User\UserRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailNewCycleMember implements ShouldQueue
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
    public function handle(CycleRepository $cycleRepo, UserRepository $userRepo, FakeMailer $mailer)
    {
        // get cycle
        $cycle = $cycleRepo->get($this->event->cycleId);

        // removes new member because we gonna send email to others
        $newMemberUserId = $this->event->userId;
        $key = $cycle->members->search(function (Member $member) use ($newMemberUserId) {
            return $member->user_id === $newMemberUserId;
        });
        $cycle->members->pull($key);
        $recipientUserIds = array_column($cycle->members->toArray(), 'id');

        $users = $userRepo->findByIds($recipientUserIds);
        $recipients = array_column($users->toArray(), 'email');

        $sender = 'no-reply@lunchtime.com';
        $subject = "New user want to join your lunch";
        $content = "<body>Check it out! <a herf='http://lunchtime.com/me/cycles'>LunchTime.com</a>";

        $mailer->send($sender, $recipients, $subject, $content);

    }
}
