<?php

namespace App\Common\Exception;

trait ContextAwareTrait
{
    /** @var array */
    protected $context;

    /**
     * Get contextual information of the exception
     *
     * @return array
     */
    final public function getContext(): array
    {
        return $this->context;
    }
}
