<?php

namespace App\Model\Cycle;

use App\Common\Exception\AbstractDomainException;

final class CycleException extends AbstractDomainException
{
    /**
     * @param null|\Throwable $previous
     * @param array           $context
     *
     * @return CycleException
     */
    public static function createLunchtimeBeforePropose(?\Throwable $previous, array $context = []): self
    {
        return new self('create_lunchtime_before_propose', 101, $previous, $context);
    }

    /**
     * @param null|\Throwable $previous
     * @param array           $context
     *
     * @return CycleException
     */
    public static function createJoinAlreadyMemberCycle(?\Throwable $previous, array $context = []): self
    {
        return new self('join_already_member', 102, $previous, $context);
    }

    /**
     * @param null|\Throwable $previous
     * @param array           $context
     *
     * @return CycleException
     */
    public static function createLeaveCycleNotMember(?\Throwable $previous, array $context = []): self
    {
        return new self('leave_cycle_not_member', 103, $previous, $context);
    }

    /**
     * @param null|\Throwable $previous
     * @param array           $context
     *
     * @return CycleException
     */
    public static function createCloseCycleHasMember(?\Throwable $previous, array $context = []): self
    {
        return new self('close_cycle_has_member', 104, $previous, $context);
    }
}
