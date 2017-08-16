<?php

namespace Tests\Feature\Api\Cycles;

use App\Model\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateCycleTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateCycleSuccess()
    {
        $cycleData = [
            'name' => 'Lunchtime Warrior',
            'propose_until' => '11:00:00',
            'lunchtime' => '12:00:00',
        ];

        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->json(
            'POST',
            '/api/cycles',
            $cycleData,
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        // expectation
        $cycleResponse = $cycleData;
        $cycleResponse['members'] = [
            ['user_id' => $user->id],
        ];

        $response->assertJson($cycleResponse);

        $this->assertDatabaseHas('cycles', $cycleData);
        $this->assertDatabaseHas('cycle_members', ['user_id' => $user->id]);
    }

    public function testCreateCycleWithoutProposeUntilSuccess()
    {
        $cycleData = [
            'name' => 'Lunchtime Warrior',
            'lunchtime' => '12:00:00',
        ];

        // default propose until is 0 minutes before lunch time
        $cycleResponse = $cycleData + ['propose_until' => '12:00:00'];

        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->json(
            'POST',
            '/api/cycles',
            $cycleData,
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJson($cycleResponse);

        $this->assertDatabaseHas('cycles', $cycleData);
    }

    public function testCreateCycleProposeUntilAfterLunchtimeFail()
    {
        $this->markTestIncomplete('tbd');
    }
}
