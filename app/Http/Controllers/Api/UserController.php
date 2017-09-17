<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageCycleRequest;
use App\Model\Cycle\Query\CyclePageQuery;
use App\Model\User\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** @var CyclePageQuery */
    private $cycleQuery;

    public function __construct(CyclePageQuery $cyclePageQuery)
    {
        $this->cycleQuery = $cyclePageQuery;
    }

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
     * @param PageCycleRequest $request
     *
     * @return array
     */
    public function getMyCycles(PageCycleRequest $request)
    {
        $userId = $request->user()->id;

        $page = $request->request->get('page');
        $searchName = $request->request->get('name');

        $result = $this->cycleQuery->queryPage($userId, $page, $searchName);

        return $result;
    }
}
