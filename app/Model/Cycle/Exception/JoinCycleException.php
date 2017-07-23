<?php

namespace App\Model\Propose\Exception;

class JoinCycleException extends \DomainException
{
    const CODES = [
        'ALREADY_JOINED' => 1,
    ];

    /** @var int */
    private $cycleId;

    public function __construct(
        $message = '',
        $code = 0,
        \Throwable $previous = null,
        int $cycleId
    ) {
        parent::__construct($message, $code, $previous);
        $this->cycleId = $cycleId;
    }
}
