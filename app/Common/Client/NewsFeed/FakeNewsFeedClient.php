<?php

namespace App\Common\Client\NewsFeed;

use Illuminate\Support\Facades\Log;

class FakeNewsFeedClient
{
    public function postMessage(int $userId, string $message)
    {
        sleep(3);

        Log::info('News Feed message posted', ['user_Id' => $userId, 'message' => $message]);
    }
}
