<?php

namespace Justimmo\Tests;

/**
 * Compares a method with the type and the forms the json gives its filter or parameter
 *
 * The forms are read from the method's @param tag: that is where a site learns that it can send a
 * list or a range. The type is judged on the value the method sends, because that is what the api
 * gets: a method may take a bool and send 1 or 0.
 */
trait DocumentedTypes
{
    /**
     * The type the argument declares in full: its @param tag, else its native type. The tag comes
     * first, since it can say more, like int[] where the native type says array
     */
    private static function declaredType(\ReflectionParameter $argument): string
    {
        $comment = (string) $argument->getDeclaringFunction()->getDocComment();

        // the type may hold spaces, like array<int, string>, so it runs up to the argument's name
        $pattern = '/@param\s+(.+?)\s+(?:\.\.\.)?\$' . preg_quote($argument->getName(), '/') . '\b/';
        if (preg_match($pattern, $comment, $match) === 1) {
            return trim($match[1]);
        }

        $type = $argument->getType();

        return $type === null ? '' : (string) $type;
    }

    /**
     * The forms a declared type offers, each with the types of one value: plain for a single value,
     * list for array<int, T> or T[], range for array{min?: T, max?: T}. A list which does not say
     * the type of its elements gets "mixed". Null is left out, a method is not called with it here
     *
     * @return array<string, array<int, string>>
     */
    private static function offeredForms(string $declared): array
    {
        $forms = [];
        foreach (self::splitTopLevel(ltrim($declared, '?'), '|') as $alternative) {
            if ($alternative === '' || $alternative === 'null') {
                continue;
            }

            if (preg_match('/^array\{(.*)\}$/s', $alternative, $match) === 1) {
                foreach (self::splitTopLevel($match[1], ',') as $entry) {
                    if (preg_match('/^(?:min|max)\??\s*:\s*(.+)$/s', $entry, $bound) === 1) {
                        $forms['range'] = [...$forms['range'] ?? [], ...self::scalarTypes($bound[1])];
                    }
                }
            } elseif (preg_match('/^(?:array|list)<(?:[^,<>{}]+,\s*)?(.+)>$/s', $alternative, $match) === 1) {
                $forms['list'] = [...$forms['list'] ?? [], ...self::scalarTypes($match[1])];
            } elseif (preg_match('/^(.+)\[\]$/', $alternative, $match) === 1) {
                $forms['list'] = [...$forms['list'] ?? [], ...self::scalarTypes($match[1])];
            } elseif ($alternative === 'array' || $alternative === 'list') {
                $forms['list'] = [...$forms['list'] ?? [], 'mixed'];
            } else {
                $forms['plain'] = [...$forms['plain'] ?? [], $alternative];
            }
        }

        return array_map(fn (array $types) => array_values(array_unique($types)), $forms);
    }

    /**
     * The forms the json allows the filter or parameter. A filter names its forms; a parameter is
     * always plain, unless it takes nothing but a list. A list_only filter needs only its list in
     * the @param, a single value may still be offered: the query sends it as a list of one
     *
     * @param array<string, mixed> $entry the filter or parameter, as in SupportedParameters
     *
     * @return array<int, string>
     */
    private static function documentedForms(array $entry): array
    {
        $forms = [];
        if (($entry['list_only'] ?? false) !== true && in_array('plain', $entry['forms'] ?? ['plain'], true)) {
            $forms[] = 'plain';
        }
        if (($entry['list'] ?? false) === true) {
            $forms[] = 'list';
        }
        if (in_array('range', $entry['forms'] ?? [], true)) {
            $forms[] = 'range';
        }

        return $forms;
    }

    /**
     * A value of the type, for a call of the method. The first value of the json's enum when it has
     * one of that type, so that the value is one the api accepts. Null for a type which says
     * nothing about what is sent, like mixed
     *
     * @param array<string, mixed> $entry
     */
    private static function sampleOf(string $type, array $entry): mixed
    {
        $first = $entry['enum'][0] ?? null;

        return match ($type) {
            'int'                            => is_int($first) ? $first : 1,
            'float'                          => 1.5,
            'bool', 'true'                   => true,
            'false'                          => false,
            'string', 'non-empty-string'     => is_string($first) ? $first : 'a',
            'numeric-string'                 => '1',
            default                          => null,
        };
    }

    /**
     * How a failure message names a type the test cannot send
     */
    private static function unclearType(string $form, string $type): string
    {
        return $form === 'list' && $type === 'mixed' ? 'a list without the type of its elements' : '"' . $type . '"';
    }

    /**
     * The value the test passes for a form
     */
    private static function valueOfForm(string $form, mixed $sample): mixed
    {
        return match ($form) {
            'list'  => [$sample],
            'range' => ['min' => $sample],
            default => $sample,
        };
    }

    /**
     * What is wrong with a value the method sent, or null. A range bound is sent on its own, as
     * filter[x_von] or filter[x_bis]
     *
     * @param array<string, mixed> $entry
     */
    private static function sentProblem(mixed $value, array $entry, bool $isRangeBound): ?string
    {
        $types = implode(' or ', $entry['types'] ?? [$entry['type']]);

        if ($isRangeBound) {
            if (!in_array('range', $entry['forms'] ?? [], true)) {
                return 'the json allows no range';
            }

            return self::isOfType($value, $entry) ? null : 'the json says ' . $types;
        }

        if (is_array($value)) {
            if (($entry['list'] ?? false) !== true) {
                return 'the json allows no list';
            }
            foreach ($value as $element) {
                if (!self::isOfType($element, $entry)) {
                    return 'the json says a list of ' . $types;
                }
            }

            return null;
        }

        if (!in_array('plain', self::documentedForms($entry), true)) {
            return ($entry['list_only'] ?? false) === true ? 'the json allows only a list' : 'the json allows no single value';
        }

        return self::isOfType($value, $entry) ? null : 'the json says ' . $types;
    }

    /**
     * Whether a value the method sends is of the json's type. The api gets text, so an integer may
     * be sent as digits in a string, like the '1' RealtyInquiryRequest::send() posts for a yes. A
     * json string takes only a string: an id sent where the api wants a code is wrong, even though
     * it would arrive as text
     *
     * @param array<string, mixed> $entry
     */
    private static function isOfType(mixed $value, array $entry): bool
    {
        foreach ($entry['types'] ?? [$entry['type']] as $type) {
            $matches = match ($type) {
                'integer' => is_int($value) || (is_string($value) && preg_match('/^-?\d+$/', $value) === 1),
                'number'  => is_int($value) || is_float($value) || (is_string($value) && is_numeric($value)),
                'string'  => is_string($value),
                'boolean' => is_bool($value),
                default   => false,
            };

            if ($matches) {
                return true;
            }
        }

        return false;
    }

    /**
     * A value for a failure message, so that "1" and 1 can be told apart
     */
    private static function describe(mixed $value): string
    {
        return (string) json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * The scalar types of a union, without null
     *
     * @return array<int, string>
     */
    private static function scalarTypes(string $union): array
    {
        return array_values(array_filter(
            self::splitTopLevel(trim($union), '|'),
            fn (string $type) => $type !== '' && $type !== 'null'
        ));
    }

    /**
     * Splits a type at the separator, but not inside <…>, {…} or (…)
     *
     * @return array<int, string>
     */
    private static function splitTopLevel(string $type, string $separator): array
    {
        $parts   = [];
        $current = '';
        $depth   = 0;

        foreach (str_split($type) as $character) {
            if (in_array($character, ['<', '{', '('], true)) {
                $depth++;
            } elseif (in_array($character, ['>', '}', ')'], true)) {
                $depth--;
            }

            if ($character === $separator && $depth === 0) {
                $parts[] = trim($current);
                $current = '';
                continue;
            }

            $current .= $character;
        }
        $parts[] = trim($current);

        return $parts;
    }
}
