<?php
namespace Justimmo\Tests\Mapper\V1;

use Justimmo\Model\Mapper\SupportedParametersInterface;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Tests\TestCase;

class SupportedParametersTest extends TestCase
{
    /**
     * The calls the generator is configured to emit
     */
    const CALLS = [
        'callRealtyList',
        'callRealtyDetail',
        'callRealtyIds',
        'callProjectList',
        'callProjectDetail',
        'callProjectIds',
        'callEmployeeList',
        'callEmployeeDetail',
        'callEmployeeIds',
    ];

    public function testEveryCallIsPresent(): void
    {
        foreach (self::CALLS as $call) {
            $this->assertArrayHasKey($call, SupportedParameters::FILTERS, $call . ' has no filters entry');
            $this->assertArrayHasKey($call, SupportedParameters::PARAMETERS, $call . ' has no parameters entry');
        }

        $this->assertCount(count(self::CALLS), SupportedParameters::FILTERS);
        $this->assertCount(count(self::CALLS), SupportedParameters::PARAMETERS);
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
            }
        }
    }

    /**
     * C2 — preis is a range filter, so the plain form has to be absent
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
     * B5 — the project filter level is readable, and only on projekt endpoints
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
     * C4 — the sort columns come from the spec, and they differ per endpoint
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
     * C7 — the picture sizes are an enum, not a hand kept list
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
     * The team endpoints take no parameters at all
     */
    public function testEmployeeCallsAreEmpty(): void
    {
        $this->assertSame([], SupportedParameters::FILTERS['callEmployeeList']);
        $this->assertSame([], SupportedParameters::PARAMETERS['callEmployeeList']);
        $this->assertSame([], SupportedParameters::FILTERS['callEmployeeIds']);
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
            'SupportedParameters.php is stale. Run bin/generate-supported-parameters.php'
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
    }
}
