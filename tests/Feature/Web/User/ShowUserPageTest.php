<?php

namespace Tests\Feature\Web\User;

use App\Model\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShowUserPageTest extends TestCase
{
    use DatabaseTransactions;

    public function testShowUserPageSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $others = factory(User::class, 10)->create();

        $this->actingAs($user);
        $response = $this->get('/user');

        $response->assertSeeText($others[0]->name);
    }

    public function testShowPaginatedUserSuccess()
    {
        // tbd
    }
}
