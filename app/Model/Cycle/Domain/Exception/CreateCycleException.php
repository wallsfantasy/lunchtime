<?php

namespace App\Model\Cycle\Domain\Exception;

class CreateCycleException extends \DomainException
{
    const CODES = [
        'LUNCHTIME_AFTER_PROPOSE_UNTIL' => 1,
    ];

    /** @var \DateInterval */
    private $proposeUntil;

    /** @var \DateInterval */
    private $lunchtime;

    public function __construct(
        $message = '',
        $code = 0,
        \Throwable $previous = null,
        \DateInterval $proposeUntil,
        \DateInterval $tallyTime
    ) {
        parent::__construct($message, $code, $previous);
        $this->proposeUntil = $proposeUntil;
        $this->lunchtime    = $tallyTime;
    }
}
