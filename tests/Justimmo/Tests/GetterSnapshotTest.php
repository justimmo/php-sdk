<?php

namespace Justimmo\Tests;

use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Pager\ListPager;

/**
 * Records what every getter returns for the synthetic api answers
 *
 * Example lines, for the realty of realty_detail.xml:
 *   Justimmo\Model\Realty
 *     getInfrastructureProvision(): 'VOLLERSCHLOSSEN'
 *     getPurchasePrice(): 1001.5
 *
 * The synthetic answers in tests/Fixtures/v1/synthetic/ fill every element the api can write for
 * their call, each with a value no other element has. This test reads each answer with the
 * wrapper the SDK uses for that call, then calls every public getter that needs no argument, on
 * the model and on every object the model returns (attachments, energy pass, additional costs,
 * the realties and the contact of a project). A public property, as on PoliticalDistrict, is
 * recorded like a getter. The fixture holds one line per call.
 *
 * So every change to what a getter returns shows as a changed line in the review, also when no
 * other test covers that getter. A release that changes a value on purpose updates the fixture in
 * the same commit.
 *
 * GETTER_SNAPSHOT_UPDATE=1, or make snapshots for all three snapshot tests, writes the fixture
 * instead of comparing against it. Review the diff
 * before committing it: every changed line is a value an integrator gets differently.
 */
class GetterSnapshotTest extends TestCase
{
    /**
     * Objects are described this deep at most; deeper ones are written by their class only
     */
    private const MAX_DEPTH = 6;

    public function testTheGettersReturnWhatTheSnapshotRecorded(): void
    {
        $lines = [];
        foreach (self::answers() as $file => $transform) {
            $lines[] = $file;
            foreach (self::describe($transform($this->getFixtures('v1/synthetic/' . $file)), 1) as $line) {
                $lines[] = $line;
            }
            $lines[] = '';
        }
        $snapshot = implode("\n", $lines);

        if (getenv('GETTER_SNAPSHOT_UPDATE')) {
            file_put_contents(__DIR__ . '/../../Fixtures/getter-snapshot.txt', $snapshot);

            $this->markTestSkipped('The fixture was written. Review its diff.');
        }

        $this->assertSame(
            $this->getFixtures('getter-snapshot.txt'),
            $snapshot,
            'A getter returns a different value. If that is intended, run make snapshots and review the diff of tests/Fixtures/getter-snapshot.txt'
        );
    }

    /**
     * Each synthetic answer, with the wrapper call the SDK makes for it
     *
     * @return array<string, callable(string): mixed>
     */
    private static function answers(): array
    {
        $realty   = new RealtyWrapper(new RealtyMapper());
        $project  = new ProjectWrapper(new ProjectMapper());
        $employee = new EmployeeWrapper(new EmployeeMapper());

        return [
            'realty_detail.xml'   => fn (string $xml) => $realty->transformSingle($xml),
            'realty_list.xml'     => fn (string $xml) => $realty->transformList($xml),
            'project_detail.xml'  => fn (string $xml) => $project->transformSingle($xml),
            'project_list.xml'    => fn (string $xml) => $project->transformList($xml),
            'employee_detail.xml' => fn (string $xml) => $employee->transformSingle($xml),
            'employee_list.xml'   => fn (string $xml) => $employee->transformList($xml),
        ];
    }

    /**
     * The lines for one value: a scalar on its own line, an array or an object with its content below
     *
     * @return array<int, string>
     */
    private static function describe(mixed $value, int $depth, string $prefix = ''): array
    {
        $indent = str_repeat('  ', $depth);

        if ($value instanceof ListPager) {
            $lines = [$indent . $prefix . ListPager::class . ', ' . $value->count() . ' entries'];
            foreach (['getNbResults', 'getMaxPerPage', 'getPage', 'getFirstPage', 'getLastPage', 'haveToPaginate'] as $method) {
                $lines = [...$lines, ...self::describeCall($value, $method, $depth + 1)];
            }
            foreach ($value as $key => $entry) {
                $lines = [...$lines, ...self::describe($entry, $depth + 1, '[' . var_export($key, true) . ']: ')];
            }

            return $lines;
        }

        if (is_array($value)) {
            $lines = [$indent . $prefix . 'array(' . count($value) . ')'];
            foreach ($value as $key => $item) {
                $lines = [...$lines, ...self::describe($item, $depth + 1, '[' . var_export($key, true) . ']: ')];
            }

            return $lines;
        }

        if (is_object($value) && str_starts_with(get_class($value), 'Justimmo\\') && $depth <= self::MAX_DEPTH) {
            $lines = [$indent . $prefix . get_class($value)];
            foreach (self::getters($value) as $method) {
                $lines = [...$lines, ...self::describeCall($value, $method, $depth + 1)];
            }
            // a value object such as PoliticalDistrict has public readonly properties instead of getters
            foreach (self::publicProperties($value) as $property) {
                $lines = [...$lines, ...self::describe($value->$property, $depth + 1, '$' . $property . ': ')];
            }

            return $lines;
        }

        return [$indent . $prefix . self::scalar($value)];
    }

    /**
     * The public getters of a model that need no argument, sorted by name
     *
     * @return array<int, string>
     */
    private static function getters(object $model): array
    {
        $reflection = new \ReflectionObject($model);
        $getters    = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (
                $method->isStatic()
                || $method->getNumberOfRequiredParameters() > 0
                || !str_starts_with($method->getDeclaringClass()->getName(), 'Justimmo\\')
                || preg_match('/^(get|is|has)[A-Z]/', $method->getName()) !== 1
            ) {
                continue;
            }
            $getters[] = $method->getName();
        }
        sort($getters);

        return $getters;
    }

    /**
     * The public properties of a model, sorted by name
     *
     * @return array<int, string>
     */
    private static function publicProperties(object $model): array
    {
        $reflection = new \ReflectionObject($model);
        $properties = [];
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $properties[] = $property->getName();
            }
        }
        sort($properties);

        return $properties;
    }

    /**
     * The lines for one getter call; a getter that throws is recorded with its exception
     *
     * @return array<int, string>
     */
    private static function describeCall(object $model, string $method, int $depth): array
    {
        try {
            $value = $model->$method();
        } catch (\Throwable $e) {
            return [str_repeat('  ', $depth) . $method . '(): throws ' . get_class($e) . ': ' . $e->getMessage()];
        }

        return self::describe($value, $depth, $method . '(): ');
    }

    /**
     * A value that is not described member by member, written the same on every PHP version
     */
    private static function scalar(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return get_class($value) . '(' . $value->format('Y-m-d H:i:s') . ')';
        }

        if ($value instanceof \SimpleXMLElement) {
            return 'SimpleXMLElement(' . var_export((string) $value, true) . ')';
        }

        if (is_object($value)) {
            return get_class($value);
        }

        if ($value === null) {
            return 'null';
        }

        return var_export($value, true);
    }
}
