<?php
namespace Justimmo\Tests\Exception;

use InvalidArgumentException;
use Justimmo\Exception\InvalidFilterValueException;
use Justimmo\Exception\JustimmoException;
use Justimmo\Tests\TestCase;

class InvalidFilterValueExceptionTest extends TestCase
{
    public function testItIsCatchableAsASdkException(): void
    {
        $exception = new InvalidFilterValueException('preis takes a range');

        $this->assertInstanceOf(JustimmoException::class, $exception);
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertSame('preis takes a range', $exception->getMessage());
    }

    public function testItCarriesTheFilterAndTheValue(): void
    {
        $exception = new InvalidFilterValueException('preis takes a range', 'preis', 455);

        $this->assertSame('preis', $exception->getFilter());
        $this->assertSame(455, $exception->getValue());
    }

    /**
     * Both are optional, so the exception can be thrown like any other
     */
    public function testTheFilterAndTheValueAreOptional(): void
    {
        $exception = new InvalidFilterValueException('something was wrong');

        $this->assertSame('', $exception->getFilter());
        $this->assertNull($exception->getValue());
    }

    /**
     * An array value is kept as it was passed, so a caller can see which element was wrong
     */
    public function testItKeepsAnArrayValue(): void
    {
        $exception = new InvalidFilterValueException('plz takes strings', 'plz', ['1010', null]);

        $this->assertSame(['1010', null], $exception->getValue());
    }
}
