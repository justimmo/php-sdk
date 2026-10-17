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
     * Sort columns a mapper sends which the spec does not document
     *
     * All four work. The api sorts by any column of the realty table, so the enum in the spec is
     * the documented subset rather than the accepted set - the controller falls through to
     * ObjektPeer::translateFieldName(), and the parity run measured all four answering ok. A name
     * which is neither a column nor a special case is answered with a 400 rather than ignored,
     * which is why this list is worth watching
     */
    const SORTS_OUTSIDE_THE_DOCUMENTED_ENUM = [
        'callRealtyList'  => ['grundflaeche', 'nutzflaeche', 'sort_flaeche', 'wohnflaeche', 'zimmer'],
        'callProjectList' => [],
    ];

    /**
     * Columns the spec documents which no method reaches
     */
    const SORTS_WITHOUT_A_METHOD = [
        'callRealtyList'  => [],
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
     * @dataProvider queryCallProvider
     */
    public function testNoMethodSendsAnUndocumentedSortColumn(string $call, string $mapper): void
    {
        $this->assertKnownDifference(
            self::SORTS_OUTSIDE_THE_DOCUMENTED_ENUM[$call],
            array_diff($this->sortColumns($mapper), $this->sortColumnsOfTheApi($call)),
            $call . ': these sort columns are sent and the spec documents none of them'
        );
    }

    /**
     * @dataProvider queryCallProvider
     */
    public function testEveryDocumentedSortColumnCanBeReached(string $call, string $mapper): void
    {
        $this->assertKnownDifference(
            self::SORTS_WITHOUT_A_METHOD[$call],
            array_diff($this->sortColumnsOfTheApi($call), $this->sortColumns($mapper)),
            $call . ': the spec documents these sort columns and no method reaches them'
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
     * The sort columns used to be read out of the filter mapping. filterByPrice now exists on a
     * project query, and orderByPrice must not start sending its filter name
     */
    public function testSortingDoesNotReadTheFilterMapping(): void
    {
        $this->assertSame('preis', (new ProjectMapper())->getFilterPropertyName('Price'));
        $this->assertSame('Price', (new ProjectMapper())->getOrderPropertyName('Price'));

        $this->assertSame(
            ['orderby' => 'Price', 'ordertype' => 'asc'],
            $this->projectQuery()->orderByPrice()->getParams()
        );
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
     * The documentation says orderByXXX() is a shortcut for orderBy('XXX'), so the two have to
     * send the same thing. They did not for UpdatedAt: the method was corrected years ago to send
     * updated_at, the mapping was not, and orderBy('UpdatedAt') sent the filter name - which the
     * api answers with a 400, since aktualisiert_am is not a column of the realty table
     */
    public function testTheTwoSortFormsAgree(): void
    {
        $mapper = new RealtyMapper();

        foreach (['Price', 'PropertyNumber', 'ZipCode', 'CreatedAt', 'PublishedAt', 'UpdatedAt', 'Rooms'] as $name) {
            $method = $this->realtyQuery()->{'orderBy' . $name}('desc')->getParams();
            $plain  = $this->realtyQuery()->orderBy($name, 'desc')->getParams();

            $this->assertSame($plain, $method, 'orderBy' . $name . '() and orderBy(\'' . $name . '\')');
            $this->assertContains($mapper->getOrderPropertyName($name), $plain);
        }
    }

    /**
     * Sorting by zimmer is a tenth of all sorting on the api and no method reached it. The range
     * filter is documented, so the column is part of the official surface
     */
    public function testRoomsCanBeSortedBy(): void
    {
        $this->assertSame(
            ['orderby' => 'zimmer', 'ordertype' => 'desc'],
            $this->realtyQuery()->orderByRooms('desc')->getParams()
        );
    }

    public function testUpdatedAtIsSortedByTheColumnAndFilteredByTheOtherName(): void
    {
        $this->assertSame(
            ['orderby' => 'updated_at', 'ordertype' => 'asc'],
            $this->realtyQuery()->orderByUpdatedAt()->getParams()
        );

        $this->assertSame(
            ['filter' => ['aktualisiert_am_von' => 1]],
            $this->realtyQuery()->filterByUpdatedAt(['min' => 1])->getParams()
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
