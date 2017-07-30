<?php

namespace App\Model\Propose\Domain;

use App\Common\Exception\AbstractDomainException;

class ProposeException extends AbstractDomainException
{
    const CODES_MAKE_PROPOSE = [
        'already_proposed' => 1,
        'restaurant_not_found' => 2,
    ];
}
