<?php

namespace App\Model\Restaurant\Application;

use App\Model\Restaurant\Restaurant;
use Illuminate\Auth\AuthManager;

class RegisterRestaurant
{
    /** @var AuthManager $authManager */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Register a restaurant
     *
     * @param string      $name
     * @param string|null $description
     *
     * @return Restaurant
     */
    public function registerRestaurant(string $name, string $description = null)
    {
        $registerUserId = $this->authManager->id();

        $restaurant = Restaurant::create(
            [
                'name' => $name,
                'register_user_id' => $registerUserId,
                'description' => $description,
            ]
        );

        return $restaurant;
    }
}
