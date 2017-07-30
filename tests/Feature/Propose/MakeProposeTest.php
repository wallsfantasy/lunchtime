<?php

namespace Tests\Feature\Propose;

use App\Model\Restaurant\Restaurant;
use App\Model\User\User;
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
                'for_date' => (new \DateTime('today'))->format('Y-m-d H:i:s'),
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $proposeData['restaurant_id'],
                'for_date' => (new \DateTime('today'))->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function testMakeProposeWithDateSuccess()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $today = new \DateTimeImmutable('today');
        $proposeData = [
            'restaurant_id' => $restaurant->id,
            'date' => $today->format('Y-m-d'),
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
                'for_date' => $today->format('Y-m-d H:i:s'),
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $proposeData['restaurant_id'],
                'for_date' => $today->format('Y-m-d H:i:s'),
            ]
        );
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
