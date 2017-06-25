<?php

namespace Tests\Feature\Cycles;

use App\Cycle;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JoinCycleTest extends TestCase
{
    use DatabaseTransactions;

    public function testJoinCycle()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var $cycle */
        $cycle = factory(Cycle::class)->create();

        $response = $this->json(
            'POST',
            '/api/cycles/join',
            [
                'cycle_id' => $cycle->id,
            ],
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJson($user->toArray());

        $this->assertDatabaseHas('cycle_user', ['user_id' => $user->id, 'cycle_id' => $cycle->id]);
    }
}
