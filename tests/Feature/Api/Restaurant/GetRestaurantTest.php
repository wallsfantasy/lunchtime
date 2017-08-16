<?php

namespace Tests\Feature\Api\Restaurant;

use App\Model\Restaurant\Restaurant;
use App\Model\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetRestaurantTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetRestaurantSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        $response = $this->json(
            'GET',
            "/api/restaurants/{$restaurant->id}",
            [],
            [
                'Authorization' => "Bearer {$user->api_token}"
            ]
        );

        $response->assertJson($restaurant->toArray());
    }
}
