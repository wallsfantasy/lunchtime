<?php

namespace App\Model\Cycle\Domain\Exception;

class CreateCycleException extends \DomainException
{
    const CODES = [
        'REPOSITORY_FAILURE' => 1,
        'LUNCHTIME_BEFORE_PROPOSE_TIME' => 102,
    ];

    /** @var array */
    private $context;

    public function __construct(
        $message = '',
        $code = 0,
        \Throwable $previous = null,
        array $context
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
