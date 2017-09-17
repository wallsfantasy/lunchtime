<?php

namespace App\Common\Mailer;

use Illuminate\Support\Facades\Log;

class FakeMailer
{
    /**
     * @param string            $sender
     * @param iterable|string[] $recipients
     * @param string            $subject
     * @param string            $content
     */
    public function send(string $sender, iterable $recipients, string $subject, string $content)
    {
        sleep(3);

        Log::info('Fake mail sent',
            ['sender' => $sender, 'recipients' => $recipients, 'subject' => $subject, 'content' => $content]
        );
    }
}
