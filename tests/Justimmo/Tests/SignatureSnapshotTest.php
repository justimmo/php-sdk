<?php

namespace Justimmo\Tests;

/**
 * The public and protected signatures of the SDK
 *
 * A subclass of an SDK class, or a class implementing one of its interfaces, is built on these
 * signatures. When one changes, such a class can stop loading with a fatal error. No other test
 * sees that, because no test has such a class. This test cannot see them either. It makes every
 * change to the SDK's side visible, so the review decides whether it needs a [BC] line.
 *
 * The fixture holds every class, interface and trait in src/Justimmo: one line for its form, then
 * one line per public or protected constant, property and method it declares itself. Inherited
 * members are listed only at the class which declares them. Only native types are recorded,
 * because only they make PHP refuse a class. Docblock types, private members and the values of
 * constants and properties are not recorded. The lines are built here and not taken from PHP's own
 * text form of a method, because that differs between PHP versions.
 *
 * SIGNATURE_SNAPSHOT_UPDATE=1 writes the fixture instead of comparing against it. Review the diff
 * before committing it: every changed line is a signature a subclass may depend on.
 */
class SignatureSnapshotTest extends TestCase
{
    public function testTheSignaturesAreWhatTheSnapshotRecorded(): void
    {
        $snapshot = implode("\n\n", array_map(
            fn (string $class) => implode("\n", self::describe(new \ReflectionClass($class))),
            self::classes()
        )) . "\n";

        if (getenv('SIGNATURE_SNAPSHOT_UPDATE')) {
            file_put_contents(__DIR__ . '/../../Fixtures/signature-snapshot.txt', $snapshot);

            $this->markTestSkipped('The fixture was written. Review its diff.');
        }

        $this->assertSame($this->getFixtures('signature-snapshot.txt'), $snapshot);
    }

    /**
     * Every class, interface and trait in src/Justimmo, by its psr-4 name
     *
     * @return array<int, class-string>
     */
    private static function classes(): array
    {
        $root  = realpath(__DIR__ . '/../../../src/Justimmo');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));

        $classes = [];
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative  = substr($file->getPathname(), strlen($root) + 1, -strlen('.php'));
            $classes[] = 'Justimmo\\' . str_replace('/', '\\', $relative);
        }
        sort($classes);

        return $classes;
    }

    /**
     * @return array<int, string>
     */
    private static function describe(\ReflectionClass $class): array
    {
        $name    = $class->getName();
        $members = [];

        foreach ($class->getReflectionConstants() as $constant) {
            if ($constant->getDeclaringClass()->getName() !== $name || $constant->isPrivate()) {
                continue;
            }

            $members['0 ' . $constant->getName()] = $name . '::'
                . ($constant->isFinal() ? 'final ' : '')
                . self::visibility($constant)
                . ' const ' . $constant->getName();
        }

        foreach ($class->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() !== $name || $property->isPrivate()) {
                continue;
            }

            $members['1 ' . $property->getName()] = $name . '::'
                . self::visibility($property)
                . ($property->isStatic() ? ' static' : '')
                . ($property->isReadOnly() ? ' readonly' : '')
                . ($property->hasType() ? ' ' . self::type($property->getType(), $class) : '')
                . ' $' . $property->getName();
        }

        foreach ($class->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() !== $name || $method->isPrivate()) {
                continue;
            }

            $parameters = array_map(fn (\ReflectionParameter $p) => self::parameter($p, $class), $method->getParameters());

            $members['2 ' . $method->getName()] = $name . '::'
                . ($method->isFinal() ? 'final ' : '')
                . ($method->isAbstract() ? 'abstract ' : '')
                . self::visibility($method)
                . ($method->isStatic() ? ' static' : '')
                . ' ' . ($method->returnsReference() ? '&' : '') . $method->getName() . '(' . implode(', ', $parameters) . ')'
                . ($method->hasReturnType() ? ': ' . self::type($method->getReturnType(), $class) : '');
        }

        ksort($members, SORT_STRING);

        return [self::form($class), ...array_values($members)];
    }

    /**
     * The line for the class itself: its kind, what it extends, implements and uses
     */
    private static function form(\ReflectionClass $class): string
    {
        $interfaces = $class->getInterfaceNames();

        if ($class->isInterface()) {
            $line = 'interface ' . $class->getName();
            if ($interfaces !== []) {
                sort($interfaces);
                $line .= ' extends ' . implode(', ', $interfaces);
            }

            return $line;
        }

        if ($class->isTrait()) {
            $line = 'trait ' . $class->getName();
        } else {
            $line   = ($class->isAbstract() ? 'abstract ' : '') . ($class->isFinal() ? 'final ' : '') . 'class ' . $class->getName();
            $parent = $class->getParentClass();
            if ($parent !== false) {
                $line .= ' extends ' . $parent->getName();

                // the interfaces of the parent are on the parent's line
                $interfaces = array_diff($interfaces, $parent->getInterfaceNames());
            }
        }

        if ($interfaces !== []) {
            sort($interfaces);
            $line .= ' implements ' . implode(', ', $interfaces);
        }

        $traits = $class->getTraitNames();
        if ($traits !== []) {
            sort($traits);
            $line .= ' uses ' . implode(', ', $traits);
        }

        return $line;
    }

    private static function visibility(\ReflectionClassConstant|\ReflectionProperty|\ReflectionMethod $member): string
    {
        return $member->isPublic() ? 'public' : 'protected';
    }

    private static function parameter(\ReflectionParameter $parameter, \ReflectionClass $class): string
    {
        $line = $parameter->hasType() ? self::type($parameter->getType(), $class) . ' ' : '';

        if ($parameter->isPassedByReference()) {
            $line .= '&';
        }
        if ($parameter->isVariadic()) {
            $line .= '...';
        }
        $line .= '$' . $parameter->getName();

        if ($parameter->isDefaultValueAvailable()) {
            $line .= ' = ' . self::value($parameter->getDefaultValue());
        }

        return $line;
    }

    /**
     * A type as it is written, with the members of a union or an intersection sorted
     *
     * self and parent are written as the class they stand for. PHP 8.5 reports self as the class
     * name and older versions report the word, so the fixture is the same on every version.
     */
    private static function type(\ReflectionType $type, \ReflectionClass $class): string
    {
        if ($type instanceof \ReflectionNamedType) {
            $name = match ($type->getName()) {
                'self'   => $class->getName(),
                'parent' => $class->getParentClass()->getName(),
                default  => $type->getName(),
            };
            $nullable = $type->allowsNull() && !in_array($name, ['mixed', 'null'], true);

            return ($nullable ? '?' : '') . $name;
        }

        if ($type instanceof \ReflectionIntersectionType) {
            $parts = array_map(fn (\ReflectionType $t) => self::type($t, $class), $type->getTypes());
            sort($parts);

            return implode('&', $parts);
        }

        if ($type instanceof \ReflectionUnionType) {
            $parts = [];
            foreach ($type->getTypes() as $part) {
                $parts[] = $part instanceof \ReflectionIntersectionType ? '(' . self::type($part, $class) . ')' : self::type($part, $class);
            }
            sort($parts);

            return implode('|', $parts);
        }

        throw new \LogicException('Unknown type ' . get_class($type));
    }

    /**
     * A default value in short array syntax, the same on every PHP version
     */
    private static function value(mixed $value): string
    {
        if (is_array($value)) {
            $parts = [];
            foreach ($value as $key => $item) {
                $parts[] = array_is_list($value) ? self::value($item) : self::value($key) . ' => ' . self::value($item);
            }

            return '[' . implode(', ', $parts) . ']';
        }

        if (is_object($value)) {
            return 'new ' . get_class($value);
        }

        if ($value === null) {
            return 'null';
        }

        return var_export($value, true);
    }
}
