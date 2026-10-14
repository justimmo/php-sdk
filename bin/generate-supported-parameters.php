<?php

/**
 * Generates the supported parameter data the SDK validates against.
 *
 * Reads data/sdk-supported-parameters.json, which the service repo writes from the hand-authored
 * spec, and writes src/Justimmo/Model/Mapper/V1/SupportedParameters.php. The output is committed.
 * The SDK never reads the json at runtime.
 *
 * The shape of the input is fixed by docs/filter-surface-contract.md. Change it on both sides or
 * not at all.
 *
 * Usage: php bin/generate-supported-parameters.php [--check]
 *
 * --check regenerates into memory and compares, without writing. It exits 1 when the committed php
 * no longer matches the committed json, which is the drift check.
 */

const INPUT          = __DIR__ . '/../data/sdk-supported-parameters.json';
const OUTPUT         = __DIR__ . '/../src/Justimmo/Model/Mapper/V1/SupportedParameters.php';
const SCHEMA_VERSION = 1;

/**
 * which endpoint each query call talks to
 *
 * The queries already return these names from getListCall(), getDetailCall() and getIdsCall(), so a
 * query passes what it has and no endpoint path has to be threaded through the SDK.
 */
const CALLS = [
    'callRealtyList'     => '/objekt/list',
    'callRealtyDetail'   => '/objekt/detail',
    'callRealtyIds'      => '/objekt/ids',
    'callProjectList'    => '/projekt/list',
    'callProjectDetail'  => '/projekt/detail',
    'callProjectIds'     => '/projekt/ids',
    'callEmployeeList'   => '/team/list',
    'callEmployeeDetail' => '/team/detail',
    'callEmployeeIds'    => '/team/ids',
];

$document = readInput(INPUT);
$php      = render($document);

if (in_array('--check', array_slice($argv, 1), true)) {
    check($php, $document);
}

if (file_put_contents(OUTPUT, $php) === false) {
    fail('cannot write ' . OUTPUT);
}

printf(
    "SupportedParameters.php: %d calls, spec %s, %s\n",
    count(CALLS),
    $document['spec_version'],
    $document['spec_hash']
);

/**
 * compares the committed file with what this run would write, and never writes
 */
function check(string $php, array $document): never
{
    $committed = is_readable(OUTPUT) ? file_get_contents(OUTPUT) : null;

    if ($committed === null) {
        fail('SupportedParameters.php does not exist. Run bin/generate-supported-parameters.php');
    }

    if ($committed !== $php) {
        fail('SupportedParameters.php and the json have drifted. Run bin/generate-supported-parameters.php');
    }

    printf("SupportedParameters.php is current, spec %s\n", $document['spec_version']);
    exit(0);
}

/**
 * @return array the decoded contract document
 */
function readInput(string $file): array
{
    if (!is_readable($file)) {
        fail("cannot read {$file}");
    }

    $document = json_decode(file_get_contents($file), true);

    if (!is_array($document)) {
        fail("{$file} is not json");
    }

    foreach (['schema_version', 'spec_version', 'spec_hash', 'endpoints'] as $key) {
        if (!isset($document[$key])) {
            fail("{$file} has no {$key}. See docs/filter-surface-contract.md");
        }
    }

    if ($document['schema_version'] !== SCHEMA_VERSION) {
        fail(sprintf(
            '%s is schema_version %s, this generator writes %d. The contract changed',
            $file,
            var_export($document['schema_version'], true),
            SCHEMA_VERSION
        ));
    }

    foreach (CALLS as $call => $endpoint) {
        if (!isset($document['endpoints'][$endpoint])) {
            fail("{$file} has no {$endpoint}, which {$call} needs");
        }
    }

    return $document;
}

/**
 * @return string the complete file, ending in a newline
 */
function render(array $document): string
{
    $filters    = [];
    $parameters = [];

    foreach (CALLS as $call => $endpoint) {
        $filters[$call]    = $document['endpoints'][$endpoint]['filters'] ?? [];
        $parameters[$call] = $document['endpoints'][$endpoint]['parameters'] ?? [];
    }

    $template = <<<'TEMPLATE'
<?php

/*
 * This file is generated. Do not edit it.
 *
 * Source:    data/sdk-supported-parameters.json, spec %1$s
 * Generator: bin/generate-supported-parameters.php
 *
 * Run `php bin/generate-supported-parameters.php --check` to find out whether the two have drifted.
 */

namespace Justimmo\Model\Mapper\V1;

/**
 * Class SupportedParameters
 *
 * What the Website API officially accepts, per query call. Data only, no behaviour
 *
 * @package Justimmo\Model\Mapper\V1
 */
final class SupportedParameters
{
    /**
     * The spec version this data was generated from
     */
    const SPEC_VERSION = '%1$s';

    /**
     * Changes when the accepted parameters change, not when a description is reworded
     */
    const SPEC_HASH = '%2$s';

    /**
     * The filters each call accepts, keyed by the api filter name without brackets and without a
     * _von or _bis suffix
     *
     * "forms" says which of filter[x], filter[x_von] and filter[x_bis] are allowed. "list" and
     * "enum" describe the plain form, because a range form is always a scalar. "level" decides
     * whether alle=1 voids the filter.
     */
    const FILTERS = %3$s;

    /**
     * Everything each call accepts that is not a filter, keyed by name
     *
     * "in" says which level of the spec declared it. The orderby enum and the picture sizes are in
     * here like any other parameter.
     */
    const PARAMETERS = %4$s;
}

TEMPLATE;

    return sprintf($template, $document['spec_version'], $document['spec_hash'], export($filters, 2), export($parameters, 2));
}

/**
 * writes a value as php source, in the style the rest of the repo uses
 *
 * A list stays on one line while it fits, because a picture size enum of eighteen strings reads
 * worse spread over eighteen lines. Everything else is one entry per line, so a regenerated diff
 * shows which filter changed.
 */
function export(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return is_string($value) ? "'" . addcslashes($value, "'\\") . "'" : var_export($value, true);
    }

    if ($value === []) {
        return '[]';
    }

    $indent  = str_repeat('    ', $depth);
    $closing = str_repeat('    ', $depth - 1);
    $isList  = array_is_list($value);

    if ($isList) {
        $inline = '[' . implode(', ', array_map(static fn ($item) => export($item, $depth + 1), $value)) . ']';

        if (!str_contains($inline, "\n") && strlen($inline) + strlen($indent) <= 110) {
            return $inline;
        }
    }

    $lines = [];

    foreach ($value as $key => $item) {
        $lines[] = $isList
            ? $indent . export($item, $depth + 1) . ','
            : $indent . export((string) $key, $depth + 1) . ' => ' . export($item, $depth + 1) . ',';
    }

    return "[\n" . implode("\n", $lines) . "\n" . $closing . ']';
}

function fail(string $message): never
{
    fwrite(STDERR, "generate-supported-parameters: {$message}\n");
    exit(1);
}
