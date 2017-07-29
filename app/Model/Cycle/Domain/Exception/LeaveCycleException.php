<?php

namespace App\Model\Cycle\Domain\Exception;

class LeaveCycleException extends \DomainException
{
    const CODES = [
        'LEAVE_NON_EXIST_CYCLE' => 1,
        'NOT_A_MEMBER' => 2,
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
