<?php declare(strict_types=1);

namespace Justimmo\Exception;

use UnexpectedValueException;

/**
 * Class InvalidResponseException
 *
 * Thrown when the api answered with a status of 200, but the body cannot be read: it is empty,
 * cut off, or not the format the call answers in, such as an HTML page from a proxy.
 *
 * It extends \Exception through UnexpectedValueException, so a catch (\Exception $e) written
 * against an earlier version, where the parser threw a plain \Exception, still catches it. The
 * error of the parser is kept as the previous exception.
 *
 * @package Justimmo\Exception
 */
class InvalidResponseException extends UnexpectedValueException implements JustimmoException
{
}
