<?php

namespace App\Common\Exception;

trait ContextAwareTrait
{
    /** @var array */
    public $exceptionContext;

    /**
     * Get contextual information of the exception
     *
     * @return array
     */
    final public function getContext(): array
    {
        return $this->exceptionContext;
    }
}
