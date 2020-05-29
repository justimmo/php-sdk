<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\MarketingState;
use Justimmo\Api\Entity\Realty\MarketingType;
use Justimmo\Api\Entity\Realty\Realty;
use Justimmo\Api\Entity\Realty\Type;

/**
 * @method $this withNumber()
 * @method $this withType()
 * @method $this withBuildingProgress()
 * @method $this withMarketingType()
 * @method $this withOccupancy()
 * @method $this withRealtyType()
 * @method $this withSubRealtyType()
 * @method $this withRealtyState()
 * @method $this withMarketingState()
 * @method $this withAddress()
 * @method $this withCoordinates()
 * @method $this withCoordinatesPrecise()
 * @method $this withCoordinatesFuzzy()
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
 * @method $this withSaleStart()
 * @method $this withAvailableFrom()
 * @method $this withMaxRentDuration()
 * @method $this withIsReference()
 * @method $this withParent()
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
 * @method $this filterByHasUndevelopedAttic($value)
 * @method $this filterByUndevelopedAtticArea($value)
 * @method $this filterByHasGarden($value)
 * @method $this filterByHasBalcony($value)
 * @method $this filterByHasTerrace($value)
 * @method $this filterByHasLoggia($value)
 * @method $this filterByHasDisabilityAccess($value)
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
class RealtyRequest extends BaseApiRequest implements SubRequest, JoinableRequest
{
    use DefaultSubRequest;

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
        'coordinatesFuzzy',
        'title',
        'coverPicture',
        'tenant',
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
        'ownershipType',
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
        'residentialAggregationData',
        'editUrl',
        'pois',
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
        'hasUndevelopedAttic',
        'undevelopedAtticArea',
        'hasGarden',
        'hasBalcony',
        'hasTerrace',
        'hasLoggia',
        'hasDisabilityAccess',
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
     * @inheritdoc
     */
    public function getJoinableFilters()
    {
        return array_key_exists('f', $this->query) ? $this->query['f'] : [];
    }

    /**
     * Filters buyable realties
     * Shortcut for $this->filterByMarketingType(Realty::MARKETING_TYPE_BUY)
     *
     * @return $this
     */
    public function buyable()
    {
        return $this->filterByMarketingType(MarketingType::BUY);
    }

    /**
     * Filters rentable realties
     * Shortcut for $this->filterByMarketingType(Realty::MARKETING_TYPE_RENT)
     *
     * @return $this
     */
    public function rentable()
    {
        return $this->filterByMarketingType(MarketingType::RENT);
    }

    /**
     * Filters leasable realties
     * Shortcut for $this->filterByMarketingType(Realty::MARKETING_TYPE_LEASE)
     *
     * @return $this
     */
    public function leasable()
    {
        return $this->filterByMarketingType(MarketingType::LEASE);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_SIMPLE);
     *
     * @return $this
     */
    public function simpleTypes()
    {
        return $this->filterByType(Type::SIMPLE);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_COMMERCIAL_PROJECT);
     *
     * @return $this
     */
    public function commercialProjects()
    {
        return $this->filterByType(Type::COMMERCIAL_PROJECT);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_COMMERCIAL_PROJECT);
     *
     * @return $this
     */
    public function residentialProjects()
    {
        return $this->filterByType(Type::RESIDENTIAL_PROJECT);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_RESIDENTIAL_SUBUNIT);
     *
     * @return $this
     */
    public function residentialSubunits()
    {
        return $this->filterByType(Type::RESIDENTIAL_SUBUNIT);
    }

    /**
     * Shortcut for $this->filterByType(Realty::TYPE_AREA);
     *
     * @return $this
     */
    public function areas()
    {
        return $this->filterByType(Type::AREA);
    }

    /**
     * Shortcut for $this->filterByMarketingState(Realty::MARKETING_STATE_TEASER);
     *
     * @return $this
     */
    public function teasered()
    {
        return $this->filterByMarketingState(MarketingState::TEASER);
    }

    /**
     * Shortcut for $this->filterByMarketingState(Realty::MARKETING_STATE_TEASER);
     *
     * @return $this
     */
    public function marketed()
    {
        return $this->filterByMarketingState(MarketingState::ACTIVE);
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
     * Return html texts for wysiwyg fields
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

    /**
     * Adds the children field
     *
     * @param RealtyRequest $request Optional filter to be executed on the children field
     *
     * @return $this
     */
    public function withChildren(RealtyRequest $request = null)
    {
        if ($request !== null) {
            $this->addSubFilter('children', $request);
        }

        return $this->with('children');
    }
}
