<?php declare(strict_types=1);

namespace Justimmo\Api;

/**
 * Interface StrictModeInterface
 *
 * An api which can be switched into strict mode, where the SDK rejects a request the api would
 * silently ignore instead of sending it.
 *
 * It is deliberately separate from JustimmoApiInterface. Adding a method to that one would be a
 * load-time fatal for anyone who implements it, so a query asks instanceof instead.
 *
 * @package Justimmo\Api
 */
interface StrictModeInterface
{
    /**
     * Switches strict mode on or off for every query built on this api
     *
     * @return $this
     */
    public function setStrict(bool $strict = true): static;

    /**
     * Whether strict mode is on
     */
    public function isStrict(): bool;
}
