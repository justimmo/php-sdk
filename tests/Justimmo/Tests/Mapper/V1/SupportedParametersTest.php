<?php
namespace Justimmo\Tests\Mapper\V1;

use Justimmo\Api\JustimmoApi;
use Justimmo\Model\Mapper\SupportedParametersInterface;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Tests\TestCase;

class SupportedParametersTest extends TestCase
{
    /**
     * The generator writes an entry for every method of JustimmoApi::ENDPOINTS, and nothing else
     */
    public function testEveryCallIsPresent(): void
    {
        $calls = array_keys(JustimmoApi::ENDPOINTS);

        foreach ($calls as $call) {
            $this->assertArrayHasKey($call, SupportedParameters::FILTERS, $call . ' has no filters entry. Run make supported-parameters');
            $this->assertArrayHasKey($call, SupportedParameters::PARAMETERS, $call . ' has no parameters entry. Run make supported-parameters');
        }

        $this->assertCount(count($calls), SupportedParameters::FILTERS);
        $this->assertCount(count($calls), SupportedParameters::PARAMETERS);
    }

    /**
     * Every filter has to carry what strict mode will read, with no surprise values
     */
    public function testFilterShape(): void
    {
        foreach (SupportedParameters::FILTERS as $call => $filters) {
            foreach ($filters as $name => $filter) {
                $where = $call . ' ' . $name;

                $this->assertArrayHasKey('forms', $filter, $where);
                $this->assertArrayHasKey('level', $filter, $where);
                $this->assertArrayHasKey('type', $filter, $where);

                $this->assertNotEmpty($filter['forms'], $where . ' has no form');
                $this->assertEmpty(array_diff($filter['forms'], ['plain', 'range']), $where . ' has an unknown form');
                $this->assertContains($filter['level'], ['realty', 'project'], $where);
                $this->assertContains($filter['type'], ['string', 'integer', 'number', 'boolean'], $where);

                if (isset($filter['list_only'])) {
                    $this->assertArrayHasKey('list', $filter, $where . ' is list only but takes no list');
                }

                $this->assertSame([], self::deprecationProblems($where, $filter, $filter['forms']));

                $this->assertStringNotContainsString('filter[', $name, $where . ' is not a bare filter name');
                $this->assertSame(
                    $name,
                    preg_replace('/_(von|bis)$/', '', $name),
                    $where . ' keeps a range suffix'
                );
            }
        }
    }

    /**
     * A range form is always a scalar, and the SDK relies on it
     */
    public function testNoRangeOnlyFilterTakesAList(): void
    {
        foreach (SupportedParameters::FILTERS as $call => $filters) {
            foreach ($filters as $name => $filter) {
                if ($filter['forms'] === ['range']) {
                    $this->assertArrayNotHasKey('list', $filter, $call . ' ' . $name . ' is range only but takes a list');
                }
            }
        }
    }

    public function testParameterShape(): void
    {
        foreach (SupportedParameters::PARAMETERS as $call => $parameters) {
            foreach ($parameters as $name => $parameter) {
                $where = $call . ' ' . $name;

                $this->assertArrayHasKey('in', $parameter, $where);
                $this->assertArrayHasKey('type', $parameter, $where);
                $this->assertContains($parameter['in'], ['path', 'operation'], $where);
                $this->assertStringNotContainsString('filter[', $name, $where . ' belongs in FILTERS');

                // a parameter has no range form
                $this->assertSame([], self::deprecationProblems($where, $parameter, ['plain']));
            }
        }
    }

    /**
     * The json has no deprecated filter or parameter yet, so the shape check runs against made-up
     * entries
     */
    public function testTheDeprecationShapeCheckFindsWrongForms(): void
    {
        $range    = ['forms' => ['range'], 'level' => 'realty', 'type' => 'number'];
        $both     = ['forms' => ['plain', 'range'], 'level' => 'realty', 'type' => 'string', 'list' => true];
        $listOnly = ['forms' => ['plain'], 'level' => 'realty', 'type' => 'integer', 'list' => true, 'list_only' => true];
        $scalar   = ['in' => 'operation', 'type' => 'integer'];
        $list     = ['in' => 'path', 'type' => 'string', 'list' => true];

        $problems = [
            ...self::deprecationProblems('a range filter', [...$range, 'deprecated' => ['plain']], $range['forms']),
            ...self::deprecationProblems('a range filter', [...$range, 'deprecated' => ['list']], $range['forms']),
            ...self::deprecationProblems('a filter with both forms', [...$both, 'deprecated' => ['range', 'plain']], $both['forms']),
            ...self::deprecationProblems('a filter with both forms', [...$both, 'deprecated' => []], $both['forms']),
            ...self::deprecationProblems('a list-only filter', [...$listOnly, 'deprecated' => ['list']], $listOnly['forms']),
            ...self::deprecationProblems('a scalar parameter', [...$scalar, 'deprecated' => ['list']], ['plain']),
            ...self::deprecationProblems('a scalar parameter', [...$scalar, 'deprecated' => ['range']], ['plain']),
        ];

        $this->assertSame([
            'a range filter: deprecated names plain, a form it does not take',
            'a range filter: deprecated names list, a form it does not take',
            'a filter with both forms: deprecated is not sorted, or repeats a form',
            'a filter with both forms: deprecated is not a list of forms',
            'a list-only filter: only the whole filter can be deprecated, ["list", "plain"]',
            'a scalar parameter: deprecated names list, a form it does not take',
            'a scalar parameter: deprecated names range, a form it does not take',
        ], $problems);

        // what the api may send
        $this->assertSame([], [
            ...self::deprecationProblems('a range filter', [...$range, 'deprecated' => ['range']], $range['forms']),
            ...self::deprecationProblems('a filter with both forms', [...$both, 'deprecated' => ['list', 'plain', 'range']], $both['forms']),
            ...self::deprecationProblems('a list-only filter', [...$listOnly, 'deprecated' => ['list', 'plain']], $listOnly['forms']),
            ...self::deprecationProblems('a scalar parameter', [...$scalar, 'deprecated' => ['plain']], ['plain']),
            ...self::deprecationProblems('a list parameter', [...$list, 'deprecated' => ['list']], ['plain']),
        ]);
    }

    /**
     * What is wrong with an entry's deprecated forms. They are a sorted, non-empty list of the forms
     * the entry takes: its forms, and list when it has "list": true. A list-only filter is deprecated
     * only as a whole
     *
     * @param array<string, mixed> $entry
     * @param array<int, string>   $forms the forms the entry takes, without list
     *
     * @return array<int, string>
     */
    private static function deprecationProblems(string $where, array $entry, array $forms): array
    {
        if (!array_key_exists('deprecated', $entry)) {
            return [];
        }

        $deprecated = $entry['deprecated'];
        if (!is_array($deprecated) || $deprecated === [] || !array_is_list($deprecated)) {
            return [$where . ': deprecated is not a list of forms'];
        }

        $problems = [];

        $sorted = array_values(array_unique($deprecated));
        sort($sorted, SORT_STRING);
        if ($sorted !== $deprecated) {
            $problems[] = $where . ': deprecated is not sorted, or repeats a form';
        }

        if (($entry['list'] ?? false) === true) {
            $forms[] = 'list';
        }
        foreach (array_diff($deprecated, $forms) as $form) {
            $problems[] = $where . ': deprecated names ' . $form . ', a form it does not take';
        }

        if (($entry['list_only'] ?? false) === true && $deprecated !== ['list', 'plain']) {
            $problems[] = $where . ': only the whole filter can be deprecated, ["list", "plain"]';
        }

        return $problems;
    }

    /**
     * preis is a range filter, so the plain form has to be absent
     */
    public function testRangeOnlyFilterHasNoPlainForm(): void
    {
        $filters = SupportedParameters::FILTERS['callRealtyList'];

        $this->assertSame(['range'], $filters['preis']['forms']);
        $this->assertSame(['range'], $filters['zimmer']['forms']);
        $this->assertSame(['range'], $filters['wohnflaeche']['forms']);
    }

    /**
     * plz is the only filter where the plain and the range form differ
     */
    public function testFilterWithBothForms(): void
    {
        $plz = SupportedParameters::FILTERS['callRealtyList']['plz'];

        $this->assertSame(['plain', 'range'], $plz['forms']);
        $this->assertTrue($plz['list'], 'the plain form of plz takes an array');
        $this->assertSame('string', $plz['type']);
    }

    /**
     * The project filter level is readable, and only on projekt endpoints
     */
    public function testProjectFilterLevel(): void
    {
        $filters = SupportedParameters::FILTERS['callProjectList'];

        $this->assertSame('project', $filters['projekt_status']['level']);
        $this->assertSame('project', $filters['referenz']['level']);
        $this->assertSame('project', $filters['fertigstellung']['level']);
        $this->assertSame('realty', $filters['preis']['level']);

        foreach (SupportedParameters::FILTERS['callRealtyList'] as $name => $filter) {
            $this->assertSame('realty', $filter['level'], $name . ' on an objekt endpoint cannot be project level');
        }

        $this->assertSame(
            array_keys($filters),
            array_keys(SupportedParameters::FILTERS['callProjectIds']),
            'projekt list and ids share one parameter set'
        );
    }

    /**
     * The sort columns come from the spec, and they differ per endpoint
     */
    public function testOrderByColumns(): void
    {
        $realty  = SupportedParameters::PARAMETERS['callRealtyList']['orderby']['enum'];
        $project = SupportedParameters::PARAMETERS['callProjectList']['orderby']['enum'];

        $this->assertContains('published_at', $realty, 'published_at is sortable and the SDK has no builder for it');
        $this->assertContains('created_at', $realty);
        $this->assertNotContains('published_at', $project, 'projekt sorts by fewer columns than objekt');
        $this->assertSame(['created_at', 'updated_at'], $project);
    }

    /**
     * The picture sizes are an enum, not a hand kept list
     */
    public function testPictureSizes(): void
    {
        $picturesize = SupportedParameters::PARAMETERS['callRealtyList']['picturesize'];

        $this->assertTrue($picturesize['list'], 'more than one size may be asked for');
        $this->assertContains('orig', $picturesize['enum']);
        $this->assertContains('medium', $picturesize['enum']);
        $this->assertNotContains('thumb', $picturesize['enum'], 'a size the api does not know');
        $this->assertSame('orig', $picturesize['default']);
    }

    /**
     * Both levels of the spec are read. Reading only the operation level loses these
     */
    public function testPathLevelParametersArePresent(): void
    {
        $parameters = SupportedParameters::PARAMETERS['callRealtyList'];

        foreach (['limit', 'offset', 'culture', 'picturesize'] as $name) {
            $this->assertArrayHasKey($name, $parameters, $name . ' is declared at the path level and was lost');
            $this->assertSame('path', $parameters[$name]['in'], $name);
        }

        $this->assertSame('operation', $parameters['orderby']['in']);
        $this->assertSame(10, $parameters['limit']['default']);
    }

    /**
     * The team endpoints take no filters. The list and the detail take only the picture size, the
     * ids nothing
     */
    public function testTheTeamEndpointsTakeOnlyThePictureSize(): void
    {
        $this->assertSame([], SupportedParameters::FILTERS['callEmployeeList']);
        $this->assertSame(['picturesize'], array_keys(SupportedParameters::PARAMETERS['callEmployeeList']));
        $this->assertSame(['id', 'picturesize'], array_keys(SupportedParameters::PARAMETERS['callEmployeeDetail']));
        $this->assertSame([], SupportedParameters::FILTERS['callEmployeeIds']);
        $this->assertSame([], SupportedParameters::PARAMETERS['callEmployeeIds']);
    }

    public function testMappersExposeTheData(): void
    {
        $pairs = [
            [new RealtyMapper(), 'callRealtyList'],
            [new ProjectMapper(), 'callProjectList'],
            [new EmployeeMapper(), 'callEmployeeList'],
        ];

        foreach ($pairs as [$mapper, $call]) {
            $this->assertInstanceOf(SupportedParametersInterface::class, $mapper);
            $this->assertSame(SupportedParameters::FILTERS[$call], $mapper->getSupportedFilters($call));
            $this->assertSame(SupportedParameters::PARAMETERS[$call], $mapper->getSupportedParameters($call));
            $this->assertSame(SupportedParameters::SPEC_VERSION, $mapper->getSpecVersion());
        }
    }

    public function testAnUnknownCallReturnsNothing(): void
    {
        $mapper = new RealtyMapper();

        $this->assertSame([], $mapper->getSupportedFilters('callSomethingElse'));
        $this->assertSame([], $mapper->getSupportedParameters('callSomethingElse'));
    }

    /**
     * The generated php and the json it came from must not drift apart
     */
    public function testTheGeneratedDataMatchesItsSource(): void
    {
        $file = __DIR__ . '/../../../../../data/sdk-supported-parameters.json';

        if (!is_readable($file)) {
            $this->markTestSkipped('data/sdk-supported-parameters.json is not installed');
        }

        $document = json_decode(file_get_contents($file), true);

        $this->assertSame(
            $document['spec_hash'],
            SupportedParameters::SPEC_HASH,
            'SupportedParameters.php is stale. Run make supported-parameters'
        );
        $this->assertSame($document['spec_version'], SupportedParameters::SPEC_VERSION);

        $this->assertSame(
            $document['endpoints']['/objekt/list']['filters'],
            SupportedParameters::FILTERS['callRealtyList']
        );
        $this->assertSame(
            $document['endpoints']['/projekt/list']['parameters'],
            SupportedParameters::PARAMETERS['callProjectList']
        );

        $deprecated = [];
        foreach (JustimmoApi::ENDPOINTS as $call => $endpoint) {
            if (($document['endpoints'][$endpoint]['deprecated'] ?? false) === true) {
                $deprecated[] = $call;
            }
        }
        sort($deprecated, SORT_STRING);

        $this->assertSame(
            $deprecated,
            SupportedParameters::DEPRECATED_CALLS,
            'SupportedParameters.php is stale. Run make supported-parameters'
        );

        // the names come sorted from the generator; the json is sorted here too, so its order does not matter
        $names = $document['user_defined_simplefields'];
        sort($names, SORT_STRING);

        $this->assertSame(
            $names,
            SupportedParameters::USER_DEFINED_SIMPLE_FIELDS,
            'SupportedParameters.php is stale. Run make supported-parameters'
        );
    }
}
