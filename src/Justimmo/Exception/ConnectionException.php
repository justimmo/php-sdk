<?php declare(strict_types=1);

namespace Justimmo\Exception;

use InvalidArgumentException;

/**
 * Class ConnectionException
 *
 * Thrown when a call did not get an answer from the api at all: the host could not be resolved or
 * reached, the connection timed out, or the TLS handshake failed. The message carries the error
 * of the transport.
 *
 * It extends InvalidArgumentException because that is what this failure threw before, so a catch
 * written against an earlier version still catches it.
 *
 * @package Justimmo\Exception
 */
class ConnectionException extends InvalidArgumentException implements JustimmoException
{
}
