<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Realty\MarketingState;
use Justimmo\Api\Entity\Realty\MarketingType;
use Justimmo\Api\Entity\Realty\Realty;
use Justimmo\Api\Entity\Realty\Type;
use Justimmo\Api\Request\RealtyRequest;

class RealtyRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Realty::class;

    const PATH_PREFIX = '/realties';

    const FIELDS = [
        'number',
        'type',
        'buildingProgress',
        'marketingType',
        'occupancy',
        'realtyType',
        'subRealtyType',
        'realtyState',
        'marketingState',
        'address',
        'coordinates',
        'coordinatesPrecise',
        'title',
        'coverPicture',
        'livingArea',
        'floorArea',
        'floorAreaFrom',
        'surfaceArea',
        'detailedAreas',
        'rooms',
        'detailedRooms',
        'texts',
        'features',
        'employee',
        'realtyCategories',
        'showPrices',
        'price',
        'priceFrom',
        'pricePerM2From',
        'pricePerM2',
        'detailedPrices',
        'energyCertificate',
        'infrastructureProvision',
        'yearOfConstruction',
        'lastRefurbishment',
        'buildingStyle',
        'condition',
        'houseCondition',
        'procuredAt',
        'createdAt',
        'updatedAt',
        'publishedAt',
        'completionDate',
        'saleStart',
        'availableFrom',
        'maxRentDuration',
        'isReference',
        'parent',
        'children',
        'showInSearch',
        'attachments',
        'links',
    ];

    const SORTS = [
        'title',
        'number',
        'price',
        'area',
        'zipCode',
        'rooms',
        'completionDate',
        'saleStart',
        'publishedAt',
        'createdAt',
        'updatedAt',
    ];

    const FILTERS = [
        'marketingType',
        'type',
        'buildingProgress',
        'realtyType',
        'subRealtyType',
        'occupancy',
        'buildingStyle',
        'realtyCategory',
        'zipCode',
        'rooms',
        'number',
        'livingArea',
        'floorArea',
        'surfaceArea',
        'overallArea',
        'country',
        'federalState',
        'realtyState',
        'marketingState',
        'city',
        'region',
        'parent',
        'price',
        'priceNet',
        'completionDate',
        'saleStart',
        'publishedAt',
        'createdAt',
        'updatedAt',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyRequest();
    }

    public function testTextFormat()
    {
        $request = $this->getRequest();

        $request->usePlainTextFormat();
        $this->assertEquals(['textFormat' => $request::TEXT_FORMAT_PLAIN], $request->getQuery());

        $request->useHtmlTextFormat();
        $this->assertEquals(['textFormat' => $request::TEXT_FORMAT_HTML], $request->getQuery());
    }

    public function testShortcutMarketingTypes()
    {
        $request = $this->getRequest();

        $request->rentable();
        $this->assertEquals(['f' => ['marketingType' => MarketingType::RENT]], $request->getQuery());

        $request->buyable();
        $this->assertEquals(['f' => ['marketingType' => MarketingType::BUY]], $request->getQuery());

        $request->leasable();
        $this->assertEquals(['f' => ['marketingType' => MarketingType::LEASE]], $request->getQuery());
    }

    public function testShortcutTypes()
    {
        $request = $this->getRequest();

        $request->simpleTypes();
        $this->assertEquals(['f' => ['type' => Type::SIMPLE]], $request->getQuery());

        $request->areas();
        $this->assertEquals(['f' => ['type' => Type::AREA]], $request->getQuery());

        $request->residentialProjects();
        $this->assertEquals(['f' => ['type' => Type::RESIDENTIAL_PROJECT]], $request->getQuery());

        $request->commercialProjects();
        $this->assertEquals(['f' => ['type' => Type::COMMERCIAL_PROJECT]], $request->getQuery());

        $request->residentialSubunits();
        $this->assertEquals(['f' => ['type' => Type::RESIDENTIAL_SUBUNIT]], $request->getQuery());
    }

    public function testShortcutWiths()
    {
        $request = $this->getRequest();
        $request->withDetailedAreas();
        $this->assertEquals(['fields' => 'livingArea,floorArea,floorAreaFrom,surfaceArea,detailedAreas'], $request->getQuery());

        $request = $this->getRequest();
        $request->withDetailedPrices();
        $this->assertEquals(['fields' => 'price,priceFrom,pricePerM2,pricePerM2From,detailedPrices'], $request->getQuery());

        $request = $this->getRequest();
        $request->withDetailedRooms();
        $this->assertEquals(['fields' => 'rooms,detailedRooms'], $request->getQuery());
    }

    public function testShortcutMarketingStates()
    {
        $request = $this->getRequest();

        $request->teasered();
        $this->assertEquals(['f' => ['marketingState' => MarketingState::TEASER]], $request->getQuery());

        $request->marketed();
        $this->assertEquals(['f' => ['marketingState' => MarketingState::ACTIVE]], $request->getQuery());
    }
}

