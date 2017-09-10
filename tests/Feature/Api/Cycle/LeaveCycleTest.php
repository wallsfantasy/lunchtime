<?php

namespace Tests\Feature\Api\Cycles;

use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use App\Model\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeaveCycleTest extends TestCase
{
    use DatabaseTransactions;

    public function testLeaveCycleSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Cycle $cycle */
        $cycle = factory(Cycle::class)->create();

        /** @var Member $member1 */
        /** @var Member $member2 */
        $member1 = factory(Member::class)->create(['user_id' => $user->id, 'cycle_id' => $cycle->id]);
        $member2 = factory(Member::class)->create(['user_id' => 0, 'cycle_id' => $cycle->id]);

        $response = $this->json(
            'DELETE',
            "/api/cycles/{$cycle->id}/leave",
            [],
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJsonMissing(['user_id' => $user->id]);

        $this->assertDatabaseMissing('cycle_members', ['user_id' => $user->id, 'cycle_id' => $cycle->id]);
    }

    public function testJoinNonExistCycleFail()
    {
        $this->markTestIncomplete('tbd');
    }

    public function testLeaveNeverJoinedCycleFail()
    {
        $this->markTestIncomplete('tbd');
    }
}
