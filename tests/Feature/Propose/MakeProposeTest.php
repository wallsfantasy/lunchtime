<?php

namespace Tests\Feature\Propose;

use App\Model\Restaurant\Restaurant;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MakeProposeTest extends TestCase
{
    use DatabaseTransactions;

    public function testMakeProposeWithoutDateSuccess()
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

    public function testMakeProposeWithDateSuccess()
    {
        $this->markTestIncomplete('tbd');
    }

    public function testMakeProposeAlreadyMadeFail()
    {
        $this->markTestIncomplete('tbd');
    }

    public function testMakeProposeNotExistRestaurantFail()
    {
        $this->markTestIncomplete('tbd');
    }
}
