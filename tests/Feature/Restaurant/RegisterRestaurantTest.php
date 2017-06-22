<?php

namespace Tests\Feature\Restaurant;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
                'Authorization' => "Bearer {$user->api_token}"
            ]
        );

        $response->assertJson([
            'success' => true,
            'data' => $restaurantData
        ]);
    }
}
