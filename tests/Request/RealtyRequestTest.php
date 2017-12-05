<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Realty;
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

    public function testIncludeAll()
    {
        $request = $this->getRequest();

        $request->includeAll();
        $this->assertEquals(['includeAll' => 1], $request->getQuery());
    }
}

