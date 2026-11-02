<?php

namespace Justimmo\Model\Query;

/**
 * Emits the deprecation notices of the query classes
 *
 * Each notice is emitted once per PHP process, which under PHP-FPM is once per request. A site
 * which nobody maintains still calls the same method on every page, and one line per class and
 * method is enough to find it in the log. A worker which runs many jobs in one process logs each notice for
 * its first job only.
 *
 * @internal
 */
trait TriggersDeprecations
{
    /**
     * The methods whose notice was emitted in this process, keyed by class and method
     *
     * @var array<string, true>
     */
    private static array $deprecationsTriggered = [];

    /**
     * @param string          $method  the deprecated method, like set or filterBypreis. A notice is
     *                                 emitted once per class and method
     * @param string|\Closure $message starts with the method, like "set() is deprecated …". The
     *                                 class is put in front. A closure is called only when the
     *                                 notice is emitted, so a message which takes work to build is
     *                                 built once
     */
    protected function triggerDeprecation(string $method, string|\Closure $message): void
    {
        $key = static::class . '::' . $method;
        if (isset(self::$deprecationsTriggered[$key])) {
            return;
        }

        self::$deprecationsTriggered[$key] = true;

        if ($message instanceof \Closure) {
            $message = $message();
        }

        $class = new \ReflectionClass($this);

        trigger_error('Since justimmo/php-sdk 1.6.0: ' . $class->getShortName() . '::' . $message, E_USER_DEPRECATED);
    }
}
