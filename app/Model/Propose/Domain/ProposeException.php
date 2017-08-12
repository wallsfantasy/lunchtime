<?php

namespace App\Model\Propose\Domain;

use App\Common\Exception\AbstractDomainException;

class ProposeException extends AbstractDomainException
{
    const CODES_MAKE_PROPOSE = [
        'already_proposed' => 1,
        'restaurant_not_found' => 2,
    ];

    const CODES_RE_PROPOSE = [
        'have_not_proposed' => 3,
        'repropose_latest_proposed' => 4,
    ];
}
