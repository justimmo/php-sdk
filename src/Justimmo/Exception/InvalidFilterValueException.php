<?php declare(strict_types=1);

namespace Justimmo\Exception;

use InvalidArgumentException;

/**
 * Class InvalidFilterValueException
 *
 * Thrown in strict mode when a known filter is handed a value it cannot take: the wrong arity, a
 * scalar where the api accepts only a range, or a value outside the enum the api documents.
 *
 * An unknown filter name is not this exception. That one stays a MethodNotFoundException, because
 * nothing was filtered at all.
 *
 * @package Justimmo\Exception
 */
class InvalidFilterValueException extends InvalidArgumentException implements JustimmoException
{
    /**
     * @param string $message what was wrong with the value
     * @param string $filter  the api filter name, without brackets and without a _von or _bis suffix
     * @param mixed  $value   the value the caller passed, kept so a form can be mapped back to it
     */
    public function __construct(
        string $message,
        private readonly string $filter = '',
        private readonly mixed $value = null
    ) {
        parent::__construct($message);
    }

    /**
     * The filter the value was meant for
     */
    public function getFilter(): string
    {
        return $this->filter;
    }

    /**
     * The value which was rejected
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
