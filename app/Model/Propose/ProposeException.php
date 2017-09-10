<?php

namespace App\Model\Propose;

use App\Common\Exception\AbstractDomainException;

class ProposeException extends AbstractDomainException
{
    const CODES_MAKE_PROPOSE = [
        'propose_limit_reach' => 101,
        'duplicate_last_propose' => 102,
    ];
}
