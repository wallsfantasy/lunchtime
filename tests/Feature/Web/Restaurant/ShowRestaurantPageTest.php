<?php

namespace Tests\Feature\Web\Restaurant;

use App\Model\Restaurant\Restaurant;
use App\Model\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShowRestaurantPageTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetRestaurantSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create(['name' => 'Amino Restaurant']);

        $this->actingAs($user);
        $response = $this->get('/restaurant');

        $response->assertSeeText($restaurant->name);
    }

    public function testGetRestaurantPaginatedSuccess()
    {
        // tbd
    }
}
