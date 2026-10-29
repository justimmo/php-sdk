<?php

namespace Justimmo\Model\Wrapper\V1;

use Exception;
use Justimmo\Exception\InvalidResponseException;
use SimpleXMLElement;

/**
 * Reads the body of an api response as XML
 *
 * The method is private on purpose. Each wrapper uses the trait itself, so a subclass of a wrapper
 * from outside the SDK cannot collide with it.
 *
 * The file does not declare strict types, like the wrappers. The constructor of SimpleXMLElement
 * takes a string, and a wrapper has always been handed whatever the api call returned. A null,
 * which an api implemented outside the SDK may return, is read as an empty body.
 */
trait ParsesXml
{
    /**
     * A body which is not XML used to escape as a plain \Exception, after three PHP warnings which
     * a framework may already have turned into an exception of its own. The warnings are kept
     * from the error handler, and the first one goes into the message instead.
     *
     * @param ?string $data the raw response of the api call
     *
     * @throws InvalidResponseException
     */
    private function parseXml(?string $data): SimpleXMLElement
    {
        $collecting = libxml_use_internal_errors(true);
        $before     = count(libxml_get_errors());

        try {
            return new SimpleXMLElement($data ?? '');
        } catch (Exception $e) {
            // only an error of this parse counts, not one a collecting caller had already
            $error  = libxml_get_errors()[$before] ?? null;
            $detail = $error !== null ? ': ' . trim($error->message) : '';

            throw new InvalidResponseException('The api answered with a body which is not valid XML' . $detail, 0, $e);
        } finally {
            // a caller which collects errors itself keeps what it has collected
            if (!$collecting) {
                libxml_clear_errors();
            }

            libxml_use_internal_errors($collecting);
        }
    }
}
