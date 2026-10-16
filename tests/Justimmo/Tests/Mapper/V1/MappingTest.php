<?php
namespace Justimmo\Tests\Mapper\V1;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\NullMapper;
use Justimmo\Model\Mapper\OrderMappingInterface;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;
use ReflectionMethod;

/**
 * Filtering and sorting are two namespaces, and the api spells them differently. These tests hold
 * the mappers to the parameters the spec documents
 */
class MappingTest extends TestCase
{
    /**
     * Filters a mapper sends which the api does not have. created_at is the only one and it is
     * deprecated in this release
     */
    const FILTERS_THE_API_DOES_NOT_HAVE = [
        'callRealtyList'  => ['created_at'],
        'callProjectList' => [],
    ];

    /**
     * Filters the api documents which no method reaches. flaeche is a second name for
     * sort_flaeche on an objekt call, and filterByArea() already sends that
     */
    const FILTERS_WITHOUT_A_METHOD = [
        'callRealtyList'  => ['flaeche'],
        'callProjectList' => [],
    ];

    /**
     * Columns the api sorts by which no method reaches. orderByUpdatedAt() sends the filter name
     * instead of updated_at, and is deprecated
     */
    const SORTS_WITHOUT_A_METHOD = [
        'callRealtyList'  => ['updated_at'],
        'callProjectList' => [],
    ];

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public function queryCallProvider(): array
    {
        return [
            'realty'  => ['callRealtyList', RealtyMapper::class],
            'project' => ['callProjectList', ProjectMapper::class],
        ];
    }

    /**
     * Nothing may be added to a mapper which the api does not accept
     *
     * @dataProvider queryCallProvider
     */
    public function testNoMethodSendsAFilterTheApiDoesNotHave(string $call, string $mapper): void
    {
        $this->assertKnownDifference(
            self::FILTERS_THE_API_DOES_NOT_HAVE[$call],
            array_diff($this->filterNames($mapper), array_keys(SupportedParameters::FILTERS[$call])),
            $call . ': these filters are sent and the api has no such parameter'
        );
    }

    /**
     * The other direction. A filter the api documents and no method reaches is a gap, and the
     * point of this release is that there were eighteen of them
     *
     * @dataProvider queryCallProvider
     */
    public function testEveryFilterTheApiHasCanBeReached(string $call, string $mapper): void
    {
        $this->assertKnownDifference(
            self::FILTERS_WITHOUT_A_METHOD[$call],
            array_diff(array_keys(SupportedParameters::FILTERS[$call]), $this->filterNames($mapper)),
            $call . ': the api documents these filters and no method sends them'
        );
    }

    /**
     * Sorting inherits the filter mapping, so a name which translates for filtering keeps
     * translating for sorting
     *
     * @dataProvider queryCallProvider
     */
    public function testEveryFilterNameStillTranslatesForSorting(string $call, string $mapper): void
    {
        $missing = array_diff_key(
            $this->mappingOf($mapper, 'getFilterMapping'),
            $this->mappingOf($mapper, 'getOrderMapping')
        );

        $this->assertSame(
            [],
            array_keys($missing),
            $call . ': these translate for filtering and would be passed through for sorting'
        );
    }

    /**
     * @dataProvider queryCallProvider
     */
    public function testEverySortColumnOfTheApiCanBeReached(string $call, string $mapper): void
    {
        $this->assertKnownDifference(
            self::SORTS_WITHOUT_A_METHOD[$call],
            array_diff($this->sortColumnsOfTheApi($call), $this->sortColumns($mapper)),
            $call . ': the api sorts by these columns and no method reaches them'
        );
    }

    /**
     * The fifteen filters the projekt endpoint documents and the SDK could not send
     */
    public function testTheProjectFiltersReachTheWire(): void
    {
        $expected = [
            'filterByBuy'                   => 'kauf',
            'filterByRent'                  => 'miete',
            'filterByRealtyTypeId'          => 'objektart_id',
            'filterBySubRealtyTypeId'       => 'subobjektart_id',
            'filterByStatusId'              => 'objekt_status_id',
            'filterByZipCode'               => 'plz',
            'filterByPropertyNumber'        => 'objektnummer',
            'filterByProjectPropertyNumber' => 'projekt_objektnummer',
            'filterByOwnerId'               => 'owner_id',
        ];

        foreach ($expected as $method => $wire) {
            $query = $this->projectQuery();

            $this->assertSame(
                ['filter' => [$wire => 1]],
                $query->$method(1)->getParams(),
                $method
            );
        }
    }

    /**
     * The range filters of the projekt endpoint. flaeche is projekt's name for the column objekt
     * calls sort_flaeche, so filterByArea means the same thing on both queries
     */
    public function testTheProjectRangeFiltersReachTheWire(): void
    {
        $expected = [
            'filterByPrice'       => 'preis',
            'filterByRooms'       => 'zimmer',
            'filterByArea'        => 'flaeche',
            'filterByLivingArea'  => 'wohnflaeche',
            'filterByFloorArea'   => 'nutzflaeche',
            'filterBySurfaceArea' => 'grundflaeche',
        ];

        foreach ($expected as $method => $wire) {
            $query = $this->projectQuery();

            $this->assertSame(
                ['filter' => [$wire . '_von' => 1, $wire . '_bis' => 2]],
                $query->$method(['min' => 1, 'max' => 2])->getParams(),
                $method
            );
        }
    }

    public function testTheNewRealtyFiltersReachTheWire(): void
    {
        $this->assertSame(
            ['filter' => ['land_id' => 40]],
            $this->realtyQuery()->filterByCountryId(40)->getParams()
        );

        $this->assertSame(
            ['filter' => ['owner_id' => 7]],
            $this->realtyQuery()->filterByOwnerId(7)->getParams()
        );
    }

    /**
     * The sort mapping inherits the filter mapping and adds the columns which are not filters
     */
    public function testSortingExtendsTheFilterMapping(): void
    {
        $mapper = new RealtyMapper();

        // a column which is sortable and not a filter
        $this->assertSame('PublishedAt', $mapper->getFilterPropertyName('PublishedAt'));
        $this->assertSame('published_at', $mapper->getOrderPropertyName('PublishedAt'));

        // and one of the many which are both
        $this->assertSame('zustand_id', $mapper->getFilterPropertyName('Condition'));
        $this->assertSame('zustand_id', $mapper->getOrderPropertyName('Condition'));
    }

    /**
     * Before the split a project query sorted by the method name, because the filter mapping had
     * no entry for it
     */
    public function testAProjectSortsByTheColumnsItHas(): void
    {
        $this->assertSame(
            ['orderby' => 'created_at', 'ordertype' => 'asc'],
            $this->projectQuery()->orderByCreatedAt()->getParams()
        );

        $this->assertSame(
            ['orderby' => 'updated_at', 'ordertype' => 'desc'],
            $this->projectQuery()->orderByUpdatedAt('desc')->getParams()
        );
    }

    public function testTheNewRealtySortReachesTheWire(): void
    {
        $this->assertSame(
            ['orderby' => 'published_at', 'ordertype' => 'asc'],
            $this->realtyQuery()->orderByPublishedAt()->getParams()
        );
    }

    /**
     * A sort column the mapper does not know is passed through, as a filter name is
     */
    public function testAnUnknownSortColumnIsPassedThrough(): void
    {
        $this->assertSame('Nonsense', (new RealtyMapper())->getOrderPropertyName('Nonsense'));
    }

    /**
     * A mapper written before 1.5.0 implements MapperInterface alone and has no sort mapping. It
     * has to keep working, and keep behaving as it did
     */
    public function testAMapperWithoutASortMappingStillWorks(): void
    {
        $mapper = new NullMapper();

        $this->assertNotInstanceOf(OrderMappingInterface::class, $mapper);

        $query = new RealtyQuery(new JustimmoNullApi(), new RealtyWrapper($mapper), $mapper);

        $this->assertSame(
            ['orderby' => 'Price', 'ordertype' => 'asc'],
            $query->orderBy('Price')->getParams()
        );
    }

    /**
     * Asserts that a difference is exactly the one we know about
     *
     * The expectation is a list rather than a count, so a new gap cannot hide behind an old one,
     * and so that closing one is a visible edit to the constant above
     *
     * @param array<int, string> $known
     * @param array<int, string> $found
     */
    private function assertKnownDifference(array $known, array $found, string $message): void
    {
        $found = array_values($found);
        sort($found);

        $this->assertSame($known, $found, $message);
    }

    /**
     * The api filter names a mapper can send, without duplicates
     *
     * @param class-string $mapper
     *
     * @return array<int, string>
     */
    private function filterNames(string $mapper): array
    {
        return array_values(array_unique($this->mappingOf($mapper, 'getFilterMapping')));
    }

    /**
     * The api sort columns a mapper can send, without duplicates
     *
     * @param class-string $mapper
     *
     * @return array<int, string>
     */
    private function sortColumns(string $mapper): array
    {
        return array_values(array_unique($this->mappingOf($mapper, 'getOrderMapping')));
    }

    /**
     * Reads one of a mapper's protected mapping tables
     *
     * @param class-string $mapper
     *
     * @return array<string, string>
     */
    private function mappingOf(string $mapper, string $method): array
    {
        /** @var array<string, string> $mapping */
        $mapping = (new ReflectionMethod($mapper, $method))->invoke(new $mapper());

        return $mapping;
    }

    /**
     * @return array<int, string>
     */
    private function sortColumnsOfTheApi(string $call): array
    {
        return SupportedParameters::PARAMETERS[$call]['orderby']['enum'];
    }

    private function realtyQuery(): RealtyQuery
    {
        $mapper = new RealtyMapper();

        return new RealtyQuery(new JustimmoNullApi(), new RealtyWrapper($mapper), $mapper);
    }

    private function projectQuery(): ProjectQuery
    {
        $mapper = new ProjectMapper();

        return new ProjectQuery(new JustimmoNullApi(), new ProjectWrapper($mapper), $mapper);
    }
}
