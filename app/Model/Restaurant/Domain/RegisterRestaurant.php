<?php

namespace App\Model\Restaurant\Domain;

use App\Model\Restaurant\Restaurant;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

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
     * @return Model|Restaurant
     */
    public function registerRestaurant(string $name, string $description = null)
    {
        $registerUserId = $this->authManager->guard()->id();

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
