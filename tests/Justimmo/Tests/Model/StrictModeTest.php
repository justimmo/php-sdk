<?php
namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Api\JustimmoNullApi;
use Justimmo\Api\StrictModeInterface;
use Justimmo\Exception\InvalidFilterValueException;
use Justimmo\Exception\MethodNotFoundException;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;

class StrictModeTest extends TestCase
{
    private function query(?JustimmoApiInterface $api = null): RealtyQuery
    {
        $mapper = new RealtyMapper();

        return new RealtyQuery($api ?: new JustimmoNullApi(), new RealtyWrapper($mapper), $mapper);
    }

    private function api(bool $strict): JustimmoApi
    {
        return (new JustimmoApi('username', 'password'))->setStrict($strict);
    }

    public function testTheApiIsNotStrictByDefault(): void
    {
        $api = new JustimmoApi('username', 'password');

        $this->assertInstanceOf(StrictModeInterface::class, $api);
        $this->assertFalse($api->isStrict());
    }

    public function testTheApiCanBeSwitched(): void
    {
        $api = new JustimmoApi('username', 'password');

        $this->assertSame($api, $api->setStrict(true));
        $this->assertTrue($api->isStrict());
        $this->assertFalse($api->setStrict(false)->isStrict());
    }

    public function testAQueryIsNotStrictByDefault(): void
    {
        $this->assertFalse($this->query()->isStrict());
    }

    public function testAQueryCanBeSwitched(): void
    {
        $query = $this->query();

        $this->assertSame($query, $query->strict());
        $this->assertTrue($query->isStrict());
        $this->assertFalse($query->strict(false)->isStrict());
    }

    public function testAQueryFollowsTheApi(): void
    {
        $this->assertTrue($this->query($this->api(true))->isStrict());
        $this->assertFalse($this->query($this->api(false))->isStrict());
    }

    /**
     * A single query can opt out of an api which has strict mode on
     */
    public function testAQueryOverridesTheApi(): void
    {
        $this->assertFalse($this->query($this->api(true))->strict(false)->isStrict());
        $this->assertTrue($this->query($this->api(false))->strict()->isStrict());
    }

    /**
     * An api implemented outside the SDK does not know about strict mode. JustimmoNullApi and the
     * test double are both such a class, and neither may make a query fatal
     */
    public function testAnApiWhichDoesNotKnowStrictModeIsNotStrict(): void
    {
        $this->assertFalse($this->query(new JustimmoNullApi())->isStrict());
        $this->assertFalse($this->query(new \Justimmo\Tests\MockJustimmoApi([]))->isStrict());
    }

    /**
     * clear() drops the filters, not how the query behaves
     */
    public function testClearKeepsStrictMode(): void
    {
        $this->assertTrue($this->query()->strict()->clear()->isStrict());
    }

    /**
     * Without strict mode an unknown name still reaches the wire, exactly as before
     */
    public function testAnUnknownFilterIsSentWhenTheQueryIsNotStrict(): void
    {
        $query = $this->query()->filterByFoo('bar');

        $this->assertSame(['filter' => ['Foo' => 'bar']], $query->getParams());
    }

    public function testAnUnknownFilterThrowsInStrictMode(): void
    {
        $this->expectException(MethodNotFoundException::class);
        $this->expectExceptionMessage('filterByFoo() is not a filter this API has.');

        $this->query()->strict()->filterByFoo('bar');
    }

    /**
     * A direct filter() call is reported by the parameter, since there is no method to name
     */
    public function testAnUnknownFilterThrowsOnTheDirectCall(): void
    {
        $this->expectException(MethodNotFoundException::class);
        $this->expectExceptionMessage('filter[foo] is not a filter this API has.');

        $this->query()->strict()->filter('foo', 'bar');
    }

    public function testAKnownFilterPassesInStrictMode(): void
    {
        $query = $this->query()->strict()->filterByZipCode('1010');

        $this->assertSame(['filter' => ['plz' => '1010']], $query->getParams());
    }

    /**
     * The generated data folds a range into its base name, so the wire names have to be let through
     */
    public function testTheWireNamesOfARangePassInStrictMode(): void
    {
        $query = $this->query()->strict()->filter('preis_von', 100)->filter('preis_bis', 200);

        $this->assertSame(['filter' => ['preis_von' => 100, 'preis_bis' => 200]], $query->getParams());
    }

    public function testARangeSuffixOnAnUnknownFilterStillThrows(): void
    {
        $this->expectException(MethodNotFoundException::class);
        $this->expectExceptionMessage('filter[foo_von] is not a filter this API has.');

        $this->query()->strict()->filter('foo_von', 100);
    }

    /**
     * The mapper maps CreatedAt, but the api documents no such filter, so it has never been applied
     */
    public function testAMappedFilterTheApiDoesNotHaveThrows(): void
    {
        $this->assertSame(
            ['filter' => ['created_at' => '2026-01-01']],
            $this->query()->filterByCreatedAt('2026-01-01')->getParams()
        );

        $this->expectException(MethodNotFoundException::class);
        $this->query()->strict()->filterByCreatedAt('2026-01-01');
    }

    /**
     * An endpoint which documents no filter leaves nothing to compare against, so nothing is
     * rejected. Making every filter fatal there would be a guess, not a rule from the spec
     */
    public function testAnEndpointWithoutFiltersRejectsNothing(): void
    {
        $mapper = new EmployeeMapper();
        $query  = new EmployeeQuery(new JustimmoNullApi(), new EmployeeWrapper($mapper), $mapper);

        $this->assertSame(
            ['filter' => ['Foo' => 'bar']],
            $query->strict()->filter('Foo', 'bar')->getParams()
        );
    }

    /**
     * The sort columns are a different list and are checked in their own right, not here
     */
    public function testOrderByIsNotCheckedAgainstTheFilters(): void
    {
        $query = $this->query()->strict()->orderByCreatedAt('desc');

        $this->assertSame(['orderby' => 'created_at', 'ordertype' => 'desc'], $query->getParams());
    }

    /**
     * The api has filter[preis_von] and filter[preis_bis] but no filter[preis], so a plain value
     * is a request which comes back unfiltered
     */
    public function testAPlainValueOnARangeFilterThrows(): void
    {
        $this->expectException(InvalidFilterValueException::class);
        $this->expectExceptionMessage('filter[preis] is a range and has no plain form.');

        $this->query()->strict()->filterByPrice(500000);
    }

    public function testTheRangeFormOfARangeFilterPasses(): void
    {
        $query = $this->query()->strict()->filterByPrice(['min' => 422000, 'max' => 696000]);

        $this->assertSame(
            ['filter' => ['preis_von' => 422000, 'preis_bis' => 696000]],
            $query->getParams()
        );
    }

    public function testAMinOrMaxOnAPlainFilterThrows(): void
    {
        $this->expectException(InvalidFilterValueException::class);
        $this->expectExceptionMessage('filter[miete] has no range');

        $this->query()->strict()->filterByRent(['min' => 0]);
    }

    public function testAListOnASingleValueFilterThrows(): void
    {
        $this->expectException(InvalidFilterValueException::class);
        $this->expectExceptionMessage('filter[stichwort] takes a single value, a list was given.');

        $this->query()->strict()->filterByKeyword(['haus', 'garten']);
    }

    public function testAListOnAListFilterPasses(): void
    {
        $query = $this->query()->strict()->filterByZipCode(['1010', '1020']);

        $this->assertSame(['filter' => ['plz' => ['1010', '1020']]], $query->getParams());
    }

    public function testAValueOutsideAnEnumThrows(): void
    {
        $this->expectException(InvalidFilterValueException::class);
        $this->expectExceptionMessage('filter[miete] does not accept 7. The api knows 0, 1.');

        $this->query()->strict()->filterByRent(7);
    }

    /**
     * An enum is matched as the wire renders it, so true, 1 and "1" are one and the same request
     */
    public function testAnEnumIsMatchedTheWayTheRequestIsSent(): void
    {
        $this->assertSame(['filter' => ['miete' => true]], $this->query()->strict()->filterByRent(true)->getParams());
        $this->assertSame(['filter' => ['miete' => '1']], $this->query()->strict()->filterByRent('1')->getParams());
        $this->assertSame(['filter' => ['miete' => false]], $this->query()->strict()->filterByRent(false)->getParams());
    }

    public function testOneEndOfARangeTakesASingleValue(): void
    {
        $this->expectException(InvalidFilterValueException::class);
        $this->expectExceptionMessage('filter[preis_von] is one end of a range');

        $this->query()->strict()->filter('preis_von', [1, 2]);
    }

    /**
     * Everything above is sent unchanged when the query is not strict
     */
    public function testNoValueIsRejectedWhenTheQueryIsNotStrict(): void
    {
        $this->assertSame(['filter' => ['preis' => 500000]], $this->query()->filterByPrice(500000)->getParams());
        $this->assertSame(['filter' => ['miete' => 7]], $this->query()->filterByRent(7)->getParams());
    }

    /**
     * The caller should not have to read the message to find out what was rejected
     */
    public function testTheExceptionNamesTheFilterAndTheValue(): void
    {
        try {
            $this->query()->strict()->filterByKeyword(['haus', 'garten']);
        } catch (InvalidFilterValueException $e) {
            $this->assertSame('stichwort', $e->getFilter());
            $this->assertSame(['haus', 'garten'], $e->getValue());

            return;
        }

        $this->fail('a list on filter[stichwort] was accepted');
    }
}
