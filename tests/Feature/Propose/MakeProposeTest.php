<?php

namespace Tests\Feature\Propose;

use App\Restaurant;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MakeProposeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMakeProposeSuccess()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $proposeData = [
            'restaurant_id' => $restaurant->id,
        ];

        $response = $this->json(
            'POST',
            '/api/proposes',
            $proposeData,
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJson(
            [
                'user_id' => $user->id,
                'restaurant_id' => $proposeData['restaurant_id'],
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $proposeData['restaurant_id'],
            ]
        );
    }
}
