<?php

namespace Tests\Feature\Api\Propose;

use App\Model\Restaurant\Restaurant;
use App\Model\User\User;
use Carbon\Carbon;
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

        $mockNow = Carbon::create(2000, 1, 1, 12);
        Carbon::setTestNow($mockNow);

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
                'for_date' => Carbon::today()->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $proposeData['restaurant_id'],
                'for_date' => Carbon::today()->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function testMakeProposeWithDateSuccess()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $mockNow = Carbon::create(2000, 1, 1, 12);
        Carbon::setTestNow($mockNow);
        $proposeData = [
            'restaurant_id' => $restaurant->id,
            'for_date' => Carbon::today()->format('Y-m-d'),
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
                'for_date' => Carbon::today()->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $proposeData['restaurant_id'],
                'for_date' => Carbon::today()->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
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
