<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;

/**
 * What find() sends for odd input, with strict mode off
 *
 * The fixture holds one line per case: the url find() requested, and the exception it ended in. It
 * was recorded with 1.4.2 and differs from it only where a release changed a request on purpose.
 * Such a change updates the fixture in the same commit, so the review shows it as a changed line.
 *
 * Every case runs on the realty query. The project and the employee query share its code and differ
 * in their mapper, so they get the cases about names, and the project query its own methods.
 * Warnings and deprecations are not recorded, because they differ between PHP versions.
 */
class WireSnapshotTest extends TestCase
{
    public function testFindSendsWhatTheSnapshotRecorded(): void
    {
        $this->assertSame($this->getFixtures('wire-snapshot.txt'), implode("\n", self::snapshot()) . "\n");
    }

    /**
     * @return array<int, string>
     */
    private static function snapshot(): array
    {
        $lines = [];

        set_error_handler(fn () => true);
        try {
            foreach (self::cases() as $name => [$type, $build]) {
                $lines[] = $name . ' => ' . self::send($type, $build);
            }
        } finally {
            restore_error_handler();
        }

        return $lines;
    }

    private static function send(string $type, callable $build): string
    {
        $api = new class('username', 'password') extends JustimmoApi {
            /**
             * @var array<int, string>
             */
            public array $urls = [];

            protected function createRequest($url)
            {
                $this->urls[] = urldecode(preg_replace('#^.*?/v1/#', '', $url));

                return new MockCurlRequest('<?xml version="1.0"?><justimmo/>', 200);
            }
        };

        $query = match ($type) {
            'realty'   => new RealtyQuery($api, new NullWrapper(), new RealtyMapper()),
            'project'  => new ProjectQuery($api, new NullWrapper(), new ProjectMapper()),
            'employee' => new EmployeeQuery($api, new NullWrapper(), new EmployeeMapper()),
        };

        // paginate() fails on the null wrapper after its request, so a url and an exception can
        // both belong to one case
        $thrown = '';
        try {
            $build($query);
            $query->find();
        } catch (\Throwable $e) {
            $thrown = 'throws ' . get_class($e);
        }

        return trim(implode(' ', $api->urls) . ' ' . $thrown);
    }

    /**
     * @return array<string, array{0: string, 1: callable(AbstractQuery): mixed}>
     */
    private static function cases(): array
    {
        $names = [
            'null' => null, 'empty' => '', 'zero' => 0, 'one' => 1, 'false' => false, 'true' => true,
            'float' => 1.5, 'stringable' => self::stringable('preis'), 'Price' => 'Price',
            'price' => 'price', 'preis' => 'preis', 'unknown' => 'Foo', 'UpdatedAt' => 'UpdatedAt',
            'CreatedAt' => 'CreatedAt', 'PublishedAt' => 'PublishedAt', 'Rooms' => 'Rooms',
            'preis_von' => 'preis_von',
        ];

        $values = [
            'null' => null, 'empty' => '', 'zero' => 0, 'zero-str' => '0', 'false' => false,
            'true' => true, 'int' => 455, 'neg' => -5, 'float' => 1.5, 'nan' => NAN, 'str' => 'abc',
            'num-str' => '12', 'stringable' => self::stringable('x'), 'object' => new \stdClass(),
            'date' => new \DateTime('2026-01-02'), 'list' => [1, 2], 'empty-list' => [],
            'list-null' => [null], 'list-empty' => ['', 'x'], 'nested' => [[1]], 'assoc' => ['a' => 1],
            'min' => ['min' => 1], 'max' => ['max' => 2], 'min-max' => ['min' => 1, 'max' => 2],
            'min-null' => ['min' => null, 'max' => 5], 'min-empty' => ['min' => '', 'max' => ''],
            'min-nested' => ['min' => [1]], 'min-stringable' => ['min' => self::stringable('3')],
            'list-stringable' => [self::stringable('a')], 'list-object' => [new \stdClass()],
        ];

        $cases = [];
        foreach (['realty', 'project', 'employee'] as $type) {
            foreach ($names as $n => $name) {
                foreach (['int' => 455, 'null' => null, 'min-max' => ['min' => 1, 'max' => 2]] as $v => $value) {
                    $cases["$type: filter(name:$n, $v)"] = [$type, fn ($q) => $q->filter($name, $value)];
                }
                $cases["$type: set(name:$n, int)"] = [$type, fn ($q) => $q->set($name, 455)];
                $cases["$type: orderBy(name:$n)"]  = [$type, fn ($q) => $q->orderBy($name)];
                $cases["$type: order(name:$n)"]    = [$type, fn ($q) => $q->order($name)];
            }
            $cases["$type: plain"] = [$type, fn ($q) => $q];
        }

        foreach ($values as $v => $value) {
            $realty = [
                "filter(preis, $v)"           => fn ($q) => $q->filter('preis', $value),
                "filterByPrice($v)"           => fn ($q) => $q->filterByPrice($value),
                "filterByFoo($v)"             => fn ($q) => $q->filterByFoo($value),
                "filterByTag($v)"             => fn ($q) => $q->filterByTag($value),
                "filterByRealtyCategory($v)"  => fn ($q) => $q->filterByRealtyCategory($value),
                "filterByEquipment($v)"       => fn ($q) => $q->filterByEquipment($value),
                "filterByCreatedAt($v)"       => fn ($q) => $q->filterByCreatedAt($value),
                "filterByZipCode($v)"         => fn ($q) => $q->filterByZipCode($value),
                "set(filter, $v)"             => fn ($q) => $q->set('filter', $value),
                "set(filter[preis], $v)"      => fn ($q) => $q->set('filter', ['preis' => $value]),
                "set(picturesize, $v)"        => fn ($q) => $q->set('picturesize', $value),
                "set(orderby, $v)"            => fn ($q) => $q->set('orderby', $value),
                "set(limit, $v)"              => fn ($q) => $q->set('limit', $value),
                "setPicturesize($v)"          => fn ($q) => $q->setPicturesize($value),
                "setLimit($v)"                => fn ($q) => $q->setLimit($value),
                "setOffset($v)"               => fn ($q) => $q->setOffset($value),
                "paginate($v)"                => fn ($q) => $q->paginate($value),
                "paginate(1, $v)"             => fn ($q) => $q->paginate(1, $value),
                "orderBy(Price, $v)"          => fn ($q) => $q->orderBy('Price', $value),
                "orderByPrice($v)"            => fn ($q) => $q->orderByPrice($value),
                "orderByUpdatedAt($v)"        => fn ($q) => $q->orderByUpdatedAt($value),
                "allProjectRealties($v)"      => fn ($q) => $q->allProjectRealties($value),
                "preciseAreaSearch($v)"       => fn ($q) => $q->preciseAreaSearch($value),
            ];
            foreach ($realty as $name => $build) {
                $cases["realty: $name"] = ['realty', $build];
            }

            $cases["project: all($v)"]                = ['project', fn ($q) => $q->all($value)];
            $cases["project: allProjectRealties($v)"] = ['project', fn ($q) => $q->allProjectRealties($value)];
            $cases["project: onlyRealtyIds($v)"]      = ['project', fn ($q) => $q->onlyRealtyIds($value)];
        }

        $cases += [
            'realty: filterBy()'                            => ['realty', fn ($q) => $q->filterBy(1)],
            'realty: filterByPrice()'                       => ['realty', fn ($q) => $q->filterByPrice()],
            'realty: filterByPrice(1, 2)'                   => ['realty', fn ($q) => $q->filterByPrice(1, 2)],
            'realty: orderByPrice()'                        => ['realty', fn ($q) => $q->orderByPrice()],
            'realty: orderByPrice(asc, x)'                  => ['realty', fn ($q) => $q->orderByPrice('asc', 'x')],
            'realty: orderBy()'                             => ['realty', fn ($q) => $q->orderBy()],
            'realty: filterByPrice(min) + filter(preis_von)' => ['realty', fn ($q) => $q->filterByPrice(['min' => 1])->filter('preis_von', 2)],
            'realty: filterByTag(a) x2'                     => ['realty', fn ($q) => $q->filterByTag('a')->filterByTag('a')],
            'realty: filterByTag(a) + filterByTag([b, a])'  => ['realty', fn ($q) => $q->filterByTag('a')->filterByTag(['b', 'a'])],
            'project: filterByPrice(1) + all()'             => ['project', fn ($q) => $q->filterByPrice(1)->all()],
        ];

        return $cases;
    }

    private static function stringable(string $value): object
    {
        return new class($value) {
            public function __construct(private string $value)
            {
            }

            public function __toString(): string
            {
                return $this->value;
            }
        };
    }
}
