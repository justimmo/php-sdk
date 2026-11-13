<?php

namespace Justimmo\Model;

/**
 * Emits the deprecation notices of the SDK's classes
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
     * The notices emitted in this process, keyed by class and method, or by class and rule
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
     * @param string          $version the SDK version which deprecated the method, like 1.8.0
     */
    protected function triggerDeprecation(string $method, string|\Closure $message, string $version = '1.6.0'): void
    {
        $this->emitDeprecation($method, function () use ($message) {
            $class = new \ReflectionClass($this);

            if ($message instanceof \Closure) {
                $message = $message();
            }

            return $class->getShortName() . '::' . $message;
        }, $version);
    }

    /**
     * Whether the deprecated method's own notice was emitted in this process
     */
    private function deprecationWasTriggered(string $method): bool
    {
        return isset(self::$deprecationsTriggered[static::class . '::' . $method]);
    }

    /**
     * Emits a notice once per class and key
     *
     * @param string   $key     what the notice is about
     * @param \Closure $message returns the notice without the version in front. Called only when
     *                          the notice is emitted
     * @param string   $version the SDK version which deprecated it, like 1.8.0
     */
    private function emitDeprecation(string $key, \Closure $message, string $version = '1.6.0'): void
    {
        $key = static::class . '::' . $key;
        if (isset(self::$deprecationsTriggered[$key])) {
            return;
        }

        self::$deprecationsTriggered[$key] = true;

        trigger_error('Since justimmo/php-sdk ' . $version . ': ' . $message(), E_USER_DEPRECATED);
    }
}
