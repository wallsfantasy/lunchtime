<?php

namespace Tests\Feature\Restaurant;

use App\Model\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterRestaurantTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateRestaurantSuccess()
    {
        $restaurantData = [
            'name' => 'Noodle Place',
            'description' => 'Best noodle in town!',
        ];

        /** @var User $user */
        $user = factory(User::class)->create();

        $response = $this->json(
            'POST',
            '/api/restaurants',
            $restaurantData,
            [
                'Authorization' => "Bearer {$user->api_token}",
            ]
        );

        $response->assertJson($restaurantData);

        $this->assertDatabaseHas('restaurants', $restaurantData);
    }
}
