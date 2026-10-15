<?php
namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Api\JustimmoNullApi;
use Justimmo\Api\StrictModeInterface;
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
}
