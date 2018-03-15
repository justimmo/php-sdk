<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty;

/**
 * @method $this withNumber()
 * @method $this withType()
 * @method $this withBuildingProgress()
 * @method $this withMarketingType()
 * @method $this withOccypancy()
 * @method $this withRealtyType()
 * @method $this withSubRealtyType()
 * @method $this withRealtyState()
 * @method $this withMarketingState()
 * @method $this withAddress()
 * @method $this withCoordinates()
 * @method $this withCoordinatesPrecise()
 * @method $this withTitle()
 * @method $this withCoverPicture()
 * @method $this withLivingArea()
 * @method $this withFloorArea()
 * @method $this withFloorAreaFrom()
 * @method $this withSurfaceArea()
 * @method $this withRooms()
 * @method $this withTexts()
 * @method $this withFeatures()
 * @method $this withEmployee()
 * @method $this withRealtyCategories()
 * @method $this withPrice()
 * @method $this withPriceFrom()
 * @method $this withPricePerM2()
 * @method $this withPricePerM2From()
 * @method $this withEnergyCertificate()
 * @method $this withInfrastructureProvision()
 * @method $this withYearOfConstruction()
 * @method $this withLastRefurbishment()
 * @method $this withBuildingStyle()
 * @method $this withCondition()
 * @method $this withHouseCondition()
 * @method $this withProcuredAt()
 * @method $this withCreatedAt()
 * @method $this withUpdatedAt()
 * @method $this withPublishedAt()
 * @method $this withCompletionDate()
 * @method $this withSaleStart()
 * @method $this withAvailableFrom()
 * @method $this withMaxRentDuration()
 * @method $this withIsReference()
 * @method $this withParent()
 * @method $this withChildren()
 * @method $this withShowInSearch()
 * @method $this withAttachments()
 * @method $this withLinks()
 * @method $this withIsReadyForOccupancy()
 * @method $this withIsReadyForFinishing()
 *
 * @method $this filterByType($value)
 * @method $this filterByBuildingProgress($value)
 * @method $this filterByMarketingType($value)
 * @method $this filterByRealtyType($value)
 * @method $this filterBySubRealtyType($value)
 * @method $this filterByOccupancy($value)
 * @method $this filterByReference($value)
 * @method $this filterByBuildingStyle($value)
 * @method $this filterByRealtyCategory($value)
 * @method $this filterByZipCode($value)
 * @method $this filterByRooms($value)
 * @method $this filterByNumber($value)
 * @method $this filterByLivingArea($value)
 * @method $this filterBySurfaceArea($value)
 * @method $this filterByFloorArea($value)
 * @method $this filterByOverallArea($value)
 * @method $this filterByCountry($value)
 * @method $this filterByFederalState($value)
 * @method $this filterByRealtyState($value)
 * @method $this filterByMarketingState($value)
 * @method $this filterByCity($value)
 * @method $this filterByRegion($value)
 * @method $this filterByParent($value)
 * @method $this filterByPrice($value)
 * @method $this filterByPriceNet($value)
 * @method $this filterByCompletionDate($value)
 * @method $this filterBySaleStart($value)
 * @method $this filterByPublishedAt($value)
 * @method $this filterByCreatedAt($value)
 * @method $this filterByUpdatedAt($value)
 *
 * @method $this sortByTitle($direction = BaseApiRequest::ASC)
 * @method $this sortByNumber($direction = BaseApiRequest::ASC)
 * @method $this sortByPrice($direction = BaseApiRequest::ASC)
 * @method $this sortByArea($direction = BaseApiRequest::ASC)
 * @method $this sortByRooms($direction = BaseApiRequest::ASC)
 * @method $this sortByCompletionDate($direction = BaseApiRequest::ASC)
 * @method $this sortBySaleStart($direction = BaseApiRequest::ASC)
 * @method $this sortByZipCode($direction = BaseApiRequest::ASC)
 * @method $this sortByPublishedAt($direction = BaseApiRequest::ASC)
 * @method $this sortByCreatedAt($direction = BaseApiRequest::ASC)
 * @method $this sortByUpdatedAt($direction = BaseApiRequest::ASC)
 */
class RealtyRequest extends BaseApiRequest
{
    const TEXT_FORMAT_PLAIN = 'plain';
    const TEXT_FORMAT_HTML  = 'html';

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
        'isReadyForOccupancy',
        'isReadyForFinishing',
    ];

    const SORTS = [
        'title',
        'number',
        'price',
        'area',
        'rooms',
        'zipCode',
        'publishedAt',
        'completionDate',
        'saleStart',
        'createdAt',
        'updatedAt',
    ];

    const FILTERS = [
        'marketingType',
        'type',
        'buildingProgress',
        'realtyType',
        'subRealtyType',
        'realtyState',
        'marketingState',
        'occupancy',
        'reference',
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
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/realties';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Realty::class;
    }

    /**
     * Filters buyable realties
     * Shortcut for $this->filterByMarketingType(Realty::MARKETING_TYPE_BUY)
     *
     * @return $this
     */
    public function buyable()
    {
        return $this->filterByMarketingType(Realty::MARKETING_TYPE_BUY);
    }

    /**
     * Filters rentable realties
     * Shortcut for $this->filterByMarketingType(Realty::MARKETING_TYPE_RENT)
     *
     * @return $this
     */
    public function rentable()
    {
        return $this->filterByMarketingType(Realty::MARKETING_TYPE_RENT);
    }

    /**
     * Filters leasable realties
     * Shortcut for $this->filterByMarketingType(Realty::MARKETING_TYPE_LEASE)
     *
     * @return $this
     */
    public function leasable()
    {
        return $this->filterByMarketingType(Realty::MARKETING_TYPE_LEASE);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_SIMPLE);
     *
     * @return $this
     */
    public function simpleTypes()
    {
        return $this->filterByType(Realty::TYPE_SIMPLE);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_COMMERCIAL_PROJECT);
     *
     * @return $this
     */
    public function commercialProjects()
    {
        return $this->filterByType(Realty::TYPE_COMMERCIAL_PROJECT);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_COMMERCIAL_PROJECT);
     *
     * @return $this
     */
    public function residentialProjects()
    {
        return $this->filterByType(Realty::TYPE_RESIDENTIAL_PROJECT);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_RESIDENTIAL_SUBUNIT);
     *
     * @return $this
     */
    public function residentialSubunits()
    {
        return $this->filterByType(Realty::TYPE_RESIDENTIAL_SUBUNIT);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_AREA);
     *
     * @return $this
     */
    public function areas()
    {
        return $this->filterByType(Realty::TYPE_AREA);
    }


    /**
     * Return plain texts for wysiwyg fields
     *
     * @return $this
     */
    public function usePlainTextFormat()
    {
        return $this->setQueryParameter('textFormat', self::TEXT_FORMAT_PLAIN);
    }

    /**
     * Return html texts for wyswyg fields
     *
     * @return $this
     */
    public function useHtmlTextFormat()
    {
        return $this->setQueryParameter('textFormat', self::TEXT_FORMAT_HTML);
    }

    /**
     * @return $this
     */
    public function withDetailedAreas()
    {
        return $this
            ->withLivingArea()
            ->withFloorArea()
            ->withFloorAreaFrom()
            ->withSurfaceArea()
            ->with('detailedAreas');
    }

    /**
     * @return $this
     */
    public function withDetailedRooms()
    {
        return $this
            ->withRooms()
            ->with('detailedRooms');
    }

    /**
     * @return $this
     */
    public function withDetailedPrices()
    {
        return $this
            ->withPrice()
            ->withPriceFrom()
            ->withPricePerM2()
            ->withPricePerM2From()
            ->with('detailedPrices');
    }
}
