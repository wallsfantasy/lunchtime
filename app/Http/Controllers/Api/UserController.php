<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Cycle\Cycle;
use App\Model\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get my User
     *
     * @param Request $request
     *
     * @return User
     */
    public function getMyUser(Request $request)
    {
        return $request->user();
    }

    /**
     * Get my Cycles
     *
     * @param Request $request
     *
     * @return Collection|Cycle[]
     */
    public function getMyCycles(Request $request)
    {
        $userId = $request->user()->id;

        $result = Cycle::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', '=', $userId);
        })
            ->with('members')
            ->get();

        return $result;
    }
}
