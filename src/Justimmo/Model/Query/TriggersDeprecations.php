<?php

namespace Justimmo\Model\Query;

/**
 * Emits the deprecation notices of the query classes
 *
 * Each notice is emitted once per request. A site which nobody maintains still calls the same
 * method on every page, and one line per method is enough to find it in the log.
 *
 * @internal
 */
trait TriggersDeprecations
{
    /**
     * The notices emitted in this request
     *
     * @var array<string, true>
     */
    private static array $deprecationsTriggered = [];

    protected function triggerDeprecation(string $message): void
    {
        $message = 'Since justimmo/php-sdk 1.6.0: ' . $message;

        if (isset(self::$deprecationsTriggered[$message])) {
            return;
        }

        self::$deprecationsTriggered[$message] = true;

        trigger_error($message, E_USER_DEPRECATED);
    }
}
