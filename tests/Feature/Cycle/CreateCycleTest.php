<?php

namespace Tests\Feature\Cycles;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateCycleTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateCycle()
    {
        $cycleData = [
            'name' => 'Lunchtime Warrior',
        ];

        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->json(
            'POST',
            '/api/cycles',
            $cycleData,
            [
                'Authorization' => "Bearer {$user->api_token}"
            ]
        );

        $response->assertJson($cycleData);

        $this->assertDatabaseHas('cycles', $cycleData);
    }
}
