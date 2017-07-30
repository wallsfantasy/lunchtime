<?php

namespace App\Model\Cycle\Domain;

use App\Common\Exception\AbstractDomainException;

class CycleException extends AbstractDomainException
{
    const CODES_CREATE_CYCLE = [
        'lunchtime_before_propose' => 101,
    ];

    const CODES_JOIN_CYCLE = [
        'join_non_exist_cycle' => 201,
        'join_already_joined' => 202,
    ];

    const CODES_LEAVE_CYCLE = [
        'leave_non_exist_cycle' => 301,
        'not_a_member' => 302,
    ];
}
