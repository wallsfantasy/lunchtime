<?php

namespace Tests\Feature\Cycles;

use App\Model\Cycle\Cycle;
use App\Model\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class JoinCycleTest extends TestCase
{
    use DatabaseTransactions;

    public function testJoinCycleSuccess()
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

        $response->assertJson(['user_id' => $user->id, 'cycle_id' => $cycle->id]);

        $this->assertDatabaseHas('cycle_members', ['user_id' => $user->id, 'cycle_id' => $cycle->id]);
    }

    public function testJoinNonExistCycleFail()
    {
        $this->markTestIncomplete('tbd');
    }
}
