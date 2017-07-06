<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty;

/**
 * @method $this sortByPublishedAt($direction = BaseApiRequest::ASC)
 */
class RealtyRequest extends BaseApiRequest
{
    const TEXT_FORMAT_PLAIN = 'plain';
    const TEXT_FORMAT_HTML  = 'html';

    const FIELDS = [
        'number',
        'type',
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
        'availableFrom',
        'maxRentDuration',
        'isReference',
        'parent',
        'showInSearch',
        'attachments',
        'links',
    ];

    const SORTS = [
        'publishedAt',
    ];

    const FILTERS = [

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
     * Return plain texts for wysiwyg fields
     *
     * @return $this
     */
    public function usePlainTextFormat()
    {
        return $this->setQueryParameter('textFormat', 'plain');
    }

    /**
     * Return html texts for wyswyg fields
     *
     * @return $this
     */
    public function useHtmlTextFormat()
    {
        return $this->setQueryParameter('textFormat', 'plain');
    }
}
