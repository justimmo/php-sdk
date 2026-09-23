<?php

namespace Justimmo\Tests\Model;

use Justimmo\Model\Mapper\V1\BasicDataMapper;
use Justimmo\Model\PoliticalDistrict;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Tests\MockJustimmoApi;
use Justimmo\Tests\TestCase;

/**
 * Every finder of the query has to call the api method which belongs to it. The mocked api
 * answers each of its calls with a different fixture, so a finder which calls the wrong one
 * ends up with data it cannot parse and the assertion of the fixture it should have used fails.
 *
 * findPoliticalDistricts() called callRegions() until 1.3.2, which nobody noticed because the
 * wrapper found no politischer_bezirk elements in a regions response and returned an empty array.
 */
class BasicDataQueryTest extends TestCase
{
    private function getQuery(): BasicDataQuery
    {
        $api = new MockJustimmoApi([
            'countries'          => $this->getFixtures('v1/countries.xml'),
            'federalStates'      => $this->getFixtures('v1/federal_states.xml'),
            'zipCodes'           => $this->getFixtures('v1/zip_codes.xml'),
            'regions'            => $this->getFixtures('v1/regions.xml'),
            'politicalDistricts' => $this->getFixtures('v1/political_districts.xml'),
            'realtyTypes'        => $this->getFixtures('v1/realty_types.xml'),
            'realtyCategories'   => $this->getFixtures('v1/categories.xml'),
            'tenant'             => $this->getFixtures('v1/tenant.xml'),
        ]);

        $mapper = new BasicDataMapper();

        return new BasicDataQuery($api, new BasicDataWrapper(), $mapper);
    }

    public function testFindCountries(): void
    {
        $countries = $this->getQuery()->findCountries();

        $this->assertCount(246, $countries);
        $this->assertSame('Deutschland', $countries[59]['name']);
    }

    public function testFindFederalStates(): void
    {
        $federalStates = $this->getQuery()->findFederalStates();

        $this->assertCount(9, $federalStates);
        $this->assertSame('Salzburg', $federalStates[130]['name']);
    }

    public function testFindZipCodes(): void
    {
        $zipCodes = $this->getQuery()->findZipCodes();

        $this->assertCount(128, $zipCodes);
        $this->assertSame('Santa Ponça', $zipCodes[0]['place']);
    }

    public function testFindRegions(): void
    {
        $regions = $this->getQuery()->findRegions();

        $this->assertNotEmpty($regions);
        $this->assertSame('1., Innere Stadt', $regions[39]);
    }

    public function testFindPoliticalDistricts(): void
    {
        $districts = $this->getQuery()->findPoliticalDistricts();

        $this->assertCount(13, $districts);
        $this->assertInstanceOf(PoliticalDistrict::class, $districts[25]);
        $this->assertSame(25, $districts[25]->id);
        $this->assertSame('Baden', $districts[25]->name);
    }

    public function testFindRealtyTypes(): void
    {
        $realtyTypes = $this->getQuery()->findRealtyTypes();

        $this->assertCount(3, $realtyTypes);
        $this->assertSame('Haus', $realtyTypes[3]['name']);
    }

    public function testFindRealtyCategories(): void
    {
        $categories = $this->getQuery()->findRealtyCategories();

        $this->assertCount(4, $categories);
        $this->assertSame('Luxusobjekte', $categories[3140]['name']);
    }

    /**
     * transformTenant() casts the xml element into an array instead of mapping it field by
     * field like every other transform of the wrapper, so all of its values are strings,
     * the id as well
     */
    public function testFindTenant(): void
    {
        $tenant = $this->getQuery()->findTenant();

        $this->assertSame('2', $tenant['id']);
        $this->assertSame('Kanzlei Mustermann', $tenant['firma']);
    }

    /**
     * The filters of the query are sent as plain parameters, not inside filter[]
     */
    public function testFilterByCountry(): void
    {
        $query = $this->getQuery()->filterByCountry(17);

        $this->assertSame(['land' => 17], $query->getParams());
    }

    public function testFilterByFederalState(): void
    {
        $query = $this->getQuery()->filterByFederalState(130);

        $this->assertSame(['bundesland' => 130], $query->getParams());
    }

    /**
     * all() compares with === true, so only the boolean switches it on
     */
    public function testAll(): void
    {
        $query = $this->getQuery()->all(true);
        $this->assertSame(['alle' => true], $query->getParams());

        $query = $this->getQuery()->all(false);
        $this->assertSame(['alle' => false], $query->getParams());
    }

    /**
     * Every finder clears the parameters, so a query object can be reused
     */
    public function testParametersAreClearedAfterACall(): void
    {
        $query = $this->getQuery();
        $query->filterByCountry(17)->findFederalStates();

        $this->assertSame([], $query->getParams());
    }
}
