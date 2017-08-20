<?php

namespace App\Common\Exception;

abstract class AbstractDomainException extends \DomainException
{
    use ContextAwareTrait;

    public function __construct($message = '', $code = 0, \Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }
}
