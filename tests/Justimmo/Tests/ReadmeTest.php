<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Model\Realty;
use Justimmo\Model\RealtyQuery;

/**
 * The examples of the README call only methods which stay. A copied example must not emit a
 * deprecation notice, and must not rely on a magic call, which 2.0.0 removes.
 */
class ReadmeTest extends TestCase
{
    /**
     * The classes whose methods the examples call
     */
    const CLASSES = [JustimmoApi::class, RealtyQuery::class, Realty::class];

    public function testTheExamplesCallOnlyMethodsWhichStay(): void
    {
        $readme = (string) file_get_contents(__DIR__ . '/../../../README.md');

        preg_match_all('/```\s*php\b(.*?)```/s', $readme, $blocks);
        $examples = implode("\n", $blocks[1]);

        preg_match_all('/->(\w+)\(/', $examples, $calls);
        $this->assertNotSame([], $calls[1]);

        $wrong = [];
        foreach (array_unique($calls[1]) as $name) {
            if (!$this->isAMethodWhichStays($name)) {
                $wrong[] = $name . '()';
            }
        }

        $this->assertSame([], $wrong);
    }

    /**
     * Whether one of the classes has the method as a real, public method which is not deprecated
     */
    private function isAMethodWhichStays(string $name): bool
    {
        foreach (self::CLASSES as $class) {
            if (!method_exists($class, $name)) {
                continue;
            }

            $method     = new \ReflectionMethod($class, $name);
            $docComment = (string) $method->getDocComment();

            if ($method->isPublic() && !str_contains($docComment, '@deprecated')) {
                return true;
            }
        }

        return false;
    }
}
