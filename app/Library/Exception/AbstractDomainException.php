<?php

namespace App\Library\Exception;

abstract class AbstractDomainException extends \DomainException
{
    /** @var array */
    protected $context;

    public function __construct($message = "", $code = 0, \Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get context of the error
     *
     * @return array
     */
    final public function getContext(): array
    {
        return $this->context;
    }
}
