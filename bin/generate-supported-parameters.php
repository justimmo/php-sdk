<?php

/**
 * Generates the supported parameter data the SDK validates against.
 *
 * Reads data/sdk-supported-parameters.json, the api's contract, and writes
 * src/Justimmo/Model/Mapper/V1/SupportedParameters.php: the filters and parameters each SDK method
 * may send, the SDK methods whose endpoint the api deprecates, and the names of the user-defined
 * simple fields. The output is committed. The SDK never reads the json at runtime.
 *
 * The json says which endpoints exist. Which SDK method sends which endpoint comes from
 * JustimmoApi::ENDPOINTS, so this script keeps no list of its own and needs composer install for
 * the autoloader. An endpoint of the json that no method sends gets a warning, and the file is
 * still written; ContractTest then fails and says what to add. CONTRIBUTING.md has the steps.
 *
 * The shape of the input is versioned by SCHEMA_VERSION. A json of another version is refused.
 *
 * Usage: php bin/generate-supported-parameters.php [--check]
 *
 * --check regenerates into memory and compares, without writing. It exits 1 when the committed php
 * no longer matches the committed json, which is the drift check.
 */

use Justimmo\Api\JustimmoApi;

require __DIR__ . '/../vendor/autoload.php';

const INPUT          = __DIR__ . '/../data/sdk-supported-parameters.json';
const OUTPUT         = __DIR__ . '/../src/Justimmo/Model/Mapper/V1/SupportedParameters.php';
const SCHEMA_VERSION = 1;

$document = readInput(INPUT);
$php      = render($document);

foreach (endpointsWithoutAMethod($document) as $endpoint) {
    fwrite(STDERR, "generate-supported-parameters: warning: {$endpoint} has no SDK method in JustimmoApi::ENDPOINTS. See CONTRIBUTING.md, \"The api adds an endpoint\"\n");
}

if (in_array('--check', array_slice($argv, 1), true)) {
    check($php, $document);
}

if (file_put_contents(OUTPUT, $php) === false) {
    fail('cannot write ' . OUTPUT);
}

printf(
    "SupportedParameters.php: %d calls, spec %s, %s\n",
    count(JustimmoApi::ENDPOINTS),
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
        fail('SupportedParameters.php does not exist. Run php bin/generate-supported-parameters.php');
    }

    if ($committed !== $php) {
        fail('SupportedParameters.php and the json have drifted. Run php bin/generate-supported-parameters.php');
    }

    printf("SupportedParameters.php is current, spec %s\n", $document['spec_version']);
    exit(0);
}

/**
 * @return array the decoded json document
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

    foreach (['schema_version', 'spec_version', 'spec_hash', 'endpoints', 'user_defined_simplefields'] as $key) {
        if (!isset($document[$key])) {
            fail("{$file} has no {$key}");
        }
    }

    if ($document['schema_version'] !== SCHEMA_VERSION) {
        fail(sprintf(
            '%s is schema_version %s, this generator writes %d. The schema changed',
            $file,
            var_export($document['schema_version'], true),
            SCHEMA_VERSION
        ));
    }

    foreach (JustimmoApi::ENDPOINTS as $call => $endpoint) {
        if (!isset($document['endpoints'][$endpoint])) {
            fail("{$file} has no {$endpoint}, which {$call} sends. Ask the api team to put it back, marked deprecated. See CONTRIBUTING.md, \"The api drops an endpoint\"");
        }
    }

    $names = $document['user_defined_simplefields'];
    if (!is_array($names) || !array_is_list($names) || array_filter($names, 'is_string') !== $names) {
        fail("{$file}: user_defined_simplefields is not a list of names");
    }

    // the generated docblock says the list is sorted, so the script sorts it, whatever order the api sends
    sort($names, SORT_STRING);
    $document['user_defined_simplefields'] = $names;

    return $document;
}

/**
 * @return string the complete file, ending in a newline
 */
function render(array $document): string
{
    $filters    = [];
    $parameters = [];
    $deprecated = [];

    foreach (JustimmoApi::ENDPOINTS as $call => $endpoint) {
        $filters[$call]    = $document['endpoints'][$endpoint]['filters'] ?? [];
        $parameters[$call] = $document['endpoints'][$endpoint]['parameters'] ?? [];

        if (($document['endpoints'][$endpoint]['deprecated'] ?? false) === true) {
            $deprecated[] = $call;
        }
    }

    sort($deprecated, SORT_STRING);

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
 * What the Website API officially accepts, per SDK method of JustimmoApi. Data only, no behaviour
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
     * "forms" says which of filter[x], filter[x_von] and filter[x_bis] are allowed. "type" is the
     * type of one value. "list", "list_only" and "enum" describe the plain form, because a range
     * form is always a scalar: "list" allows a list, "list_only" allows nothing else. "level"
     * decides whether alle=1 voids the filter.
     */
    const FILTERS = %3$s;

    /**
     * Everything each call accepts that is not a filter, keyed by name
     *
     * "in" says which level of the spec declared it. The orderby enum and the picture sizes are in
     * here like any other parameter.
     */
    const PARAMETERS = %4$s;

    /**
     * The names of the user-defined simple fields the api sends: fields Justimmo adds to the
     * OpenImmo standard, each sent with its name, sorted
     */
    const USER_DEFINED_SIMPLE_FIELDS = %5$s;

    /**
     * The SDK methods whose endpoint the api marks deprecated, sorted. Each is deprecated in the SDK
     * too, with a notice
     */
    const DEPRECATED_CALLS = %6$s;
}

TEMPLATE;

    return sprintf(
        $template,
        $document['spec_version'],
        $document['spec_hash'],
        export($filters, 2),
        export($parameters, 2),
        export($document['user_defined_simplefields'], 2),
        export($deprecated, 2)
    );
}

/**
 * The endpoints of the json which no SDK method sends, apart from the ones the api lists as
 * undocumented
 *
 * @return array<int, string>
 */
function endpointsWithoutAMethod(array $document): array
{
    return array_values(array_diff(
        array_keys($document['endpoints']),
        JustimmoApi::ENDPOINTS,
        array_keys($document['undocumented_endpoints'] ?? [])
    ));
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
