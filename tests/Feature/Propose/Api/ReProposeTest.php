<?php

namespace Tests\Feature\Propose\Api;

use App\Model\Propose\Propose;
use App\Model\Restaurant\Restaurant;
use App\Model\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReProposeTest extends TestCase
{
    use DatabaseTransactions;

    public function testReProposeWithoutForDateSuccess()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        /** @var Restaurant $newRestaurant */
        $newRestaurant = factory(Restaurant::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $mockNow = Carbon::create(2000, 1, 1, 12);
        Carbon::setTestNow($mockNow);

        $proposeData = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'for_date' => Carbon::today()->format('Y-m-d'),
            'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        /** @var Propose $proposeData */
        $propose = factory(Propose::class)->create($proposeData);

        $requestData = [
            'restaurant_id' => $newRestaurant->id,
        ];

        $response = $this->json(
            'POST',
            '/api/proposes/repropose',
            $requestData,
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJson(
            [
                'user_id' => $user->id,
                'restaurant_id' => $newRestaurant->id,
                'for_date' => Carbon::today()->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $newRestaurant->id,
                'for_date' => Carbon::today()->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function testReProposeWithForDateSuccess()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        /** @var Restaurant $newRestaurant */
        $newRestaurant = factory(Restaurant::class)->create();

        /** @var User $user */
        $user = factory(User::class)->create();

        $mockNow = Carbon::create(2000, 1, 1, 12);
        Carbon::setTestNow($mockNow);

        $tomorrow = Carbon::tomorrow();
        $proposeData = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'for_date' => $tomorrow->format('Y-m-d'),
            'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        /** @var Propose $proposeData */
        $propose = factory(Propose::class)->create($proposeData);

        $requestData = [
            'restaurant_id' => $newRestaurant->id,
            'for_date' => $tomorrow->format('Y-m-d'),
        ];

        $response = $this->json(
            'POST',
            '/api/proposes/repropose',
            $requestData,
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJson(
            [
                'user_id' => $user->id,
                'restaurant_id' => $newRestaurant->id,
                'for_date' => $tomorrow->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        $this->assertDatabaseHas(
            'proposes',
            [
                'user_id' => $user->id,
                'restaurant_id' => $newRestaurant->id,
                'for_date' => $tomorrow->format('Y-m-d H:i:s'),
                'proposed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function testReProposeWithoutPriorProposeFail()
    {
        $this->markTestIncomplete('tbd');
    }

    public function testReProposeLatestProposedFail()
    {
        $this->markTestIncomplete('tbd');
    }

    public function testReProposeNotExistRestaurantFail()
    {
        $this->markTestIncomplete('tbd');
    }
}
