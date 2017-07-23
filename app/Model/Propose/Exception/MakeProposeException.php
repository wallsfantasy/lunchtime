<?php

namespace App\Model\Propose\Exception;

class MakeProposeException extends \DomainException
{
    const CODES = [
        'ALREADY_PROPOSED' => 1,
        'RESTAURANT_NOT_FOUND' => 2,
    ];

    /** @var \DateTime */
    private $forDate;

    /** @var int */
    private $restaurantId;

    public function __construct(
        $message = '',
        $code = 0,
        \Throwable $previous = null,
        \DateTime $forDate,
        int $restaurantId
    ) {
        parent::__construct($message, $code, $previous);
        $this->forDate = $forDate;
        $this->restaurantId = $restaurantId;
    }
}
