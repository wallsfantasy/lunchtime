<?php

namespace App\Model\Propose\Exception;

class JoinCycleException extends \DomainException
{
    const CODES = [
        'JOIN_NON_EXIST_CYCLE' => 1,
        'ALREADY_JOINED' => 2,
    ];

    /** @var int */
    private $cycleId;

    /** @var int */
    private $userId;

    public function __construct(
        $message = '',
        $code = 0,
        \Throwable $previous = null,
        int $cycleId,
        int $userId
    ) {
        parent::__construct($message, $code, $previous);
        $this->cycleId = $cycleId;
        $this->userId = $userId;
    }
}
