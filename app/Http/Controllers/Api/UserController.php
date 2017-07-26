<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Cycle\Cycle;
use App\Model\Cycle\Member;
use App\Model\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Get my Members
     *
     * @param Request $request
     *
     * @return Collection|Member[]
     */
    public function getMyMembers(Request $request)
    {
        return Member::where(['user_id' => $request->user()->id])->get();
    }

    public function getMyCycles(Request $request)
    {
//        $result = DB::table('cycles')
//            ->join('cycle_members', 'cycles.id', '=', 'cycle_members.cycle_id')
//            ->select('cycles.*')
//            ->where('cycle_members.user_id', '=', $request->user()->id)
//            ->get();
        DB::connection()->enableQueryLog();

        $userId = $request->user()->id;
        $result = Cycle::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', '=', $userId);
        })
            ->with('members')
            ->get();

        $queries = DB::getQueryLog();
//        dd($queries);
        return $result;
    }
}
