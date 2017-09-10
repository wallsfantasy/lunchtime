<?php

namespace App\Model\Cycle;

use App\Common\Exception\AbstractDomainException;

class CycleException extends AbstractDomainException
{
    const CODES_CREATE_CYCLE = [
        'lunchtime_before_propose' => 101,
    ];

    const CODES_JOIN_CYCLE = [
        'join_already_joined' => 201,
    ];

    const CODES_LEAVE_CYCLE = [
        'not_a_member' => 301,
    ];

    const CODES_CLOSE_CYCLE = [
        'close_cycle_having_member' => 401,
    ];
}
