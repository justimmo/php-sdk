<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class RealtyQuery
 * @package Justimmo\Model
 *
 * @method Realty findPk($pk)
 * @method Realty|null findOne($pk)
 */
class RealtyQuery extends AbstractQuery
{
    /**
     * @inheritdoc
     */
    public function getListCall()
    {
        return 'callRealtyList';
    }

    /**
     * @inheritdoc
     */
    public function getDetailCall()
    {
        return 'callRealtyDetail';
    }

    /**
     * @inheritdoc
     */
    public function getIdsCall()
    {
        return 'callRealtyIds';
    }

    /**
     * Filters by the price, filter[preis_von] and filter[preis_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByPrice($value)
    {
        return $this->filterThroughMapper('Price', $value, 'filterByPrice');
    }

    /**
     * Filters by the realty type, filter[objektart_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByRealtyTypeId($value)
    {
        return $this->filterThroughMapper('RealtyTypeId', $value, 'filterByRealtyTypeId');
    }

    /**
     * Filters by the sub realty type, filter[subobjektart_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterBySubRealtyTypeId($value)
    {
        return $this->filterThroughMapper('SubRealtyTypeId', $value, 'filterBySubRealtyTypeId');
    }

    /**
     * Filters by the style of building, filter[bauart_id]
     *
     * @param int $value 1 or 2
     *
     * @return $this
     */
    public function filterByStyleOfBuildingId($value)
    {
        return $this->filterThroughMapper('StyleOfBuildingId', $value, 'filterByStyleOfBuildingId');
    }

    /**
     * Filters by the zip code, filter[plz], or filter[plz_von] and filter[plz_bis]
     *
     * @param string|array<int, string>|array{min?: string, max?: string} $value one zip code, a list of them, or a range
     *
     * @return $this
     */
    public function filterByZipCode($value)
    {
        return $this->filterThroughMapper('ZipCode', $value, 'filterByZipCode');
    }

    /**
     * Filters by the number of rooms, filter[zimmer_von] and filter[zimmer_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByRooms($value)
    {
        return $this->filterThroughMapper('Rooms', $value, 'filterByRooms');
    }

    /**
     * Filters by the property number, filter[objektnummer], or filter[objektnummer_von] and filter[objektnummer_bis]
     *
     * @param string|array{min?: string, max?: string} $value one property number, or a range
     *
     * @return $this
     */
    public function filterByPropertyNumber($value)
    {
        return $this->filterThroughMapper('PropertyNumber', $value, 'filterByPropertyNumber');
    }

    /**
     * Filters by the area, filter[sort_flaeche_von] and filter[sort_flaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByArea($value)
    {
        return $this->filterThroughMapper('Area', $value, 'filterByArea');
    }

    /**
     * Filters by the living area, filter[wohnflaeche_von] and filter[wohnflaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByLivingArea($value)
    {
        return $this->filterThroughMapper('LivingArea', $value, 'filterByLivingArea');
    }

    /**
     * Filters by the floor area, filter[nutzflaeche_von] and filter[nutzflaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByFloorArea($value)
    {
        return $this->filterThroughMapper('FloorArea', $value, 'filterByFloorArea');
    }

    /**
     * Filters by the surface area, filter[grundflaeche_von] and filter[grundflaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterBySurfaceArea($value)
    {
        return $this->filterThroughMapper('SurfaceArea', $value, 'filterBySurfaceArea');
    }

    /**
     * Filters by the number of gardens, filter[anzahl_garten_von] and filter[anzahl_garten_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByGardenCount($value)
    {
        return $this->filterThroughMapper('GardenCount', $value, 'filterByGardenCount');
    }

    /**
     * Filters by the number of balconies, filter[anzahl_balkons_von] and filter[anzahl_balkons_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByBalconyCount($value)
    {
        return $this->filterThroughMapper('BalconyCount', $value, 'filterByBalconyCount');
    }

    /**
     * Filters by the number of loggias, filter[anzahl_loggias_von] and filter[anzahl_loggias_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByLoggiaCount($value)
    {
        return $this->filterThroughMapper('LoggiaCount', $value, 'filterByLoggiaCount');
    }

    /**
     * Filters by the number of terraces, filter[anzahl_terrassen_von] and filter[anzahl_terrassen_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByTerraceCount($value)
    {
        return $this->filterThroughMapper('TerraceCount', $value, 'filterByTerraceCount');
    }

    /**
     * Filters by the number of cellars, filter[anzahl_keller_von] and filter[anzahl_keller_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByCellarCount($value)
    {
        return $this->filterThroughMapper('CellarCount', $value, 'filterByCellarCount');
    }

    /**
     * Filters by the number of garages, filter[anzahl_garagen_von] and filter[anzahl_garagen_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByGarageCount($value)
    {
        return $this->filterThroughMapper('GarageCount', $value, 'filterByGarageCount');
    }

    /**
     * Filters by the number of parking spaces, filter[anzahl_stellplaetze_von] and filter[anzahl_stellplaetze_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByParkingCount($value)
    {
        return $this->filterThroughMapper('ParkingCount', $value, 'filterByParkingCount');
    }

    /**
     * Filters by the number of separate toilets, filter[anzahl_sep_wc_von] and filter[anzahl_sep_wc_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByToiletRoomCount($value)
    {
        return $this->filterThroughMapper('ToiletRoomCount', $value, 'filterByToiletRoomCount');
    }

    /**
     * Filters by the number of bathrooms, filter[anzahl_badezimmer_von] and filter[anzahl_badezimmer_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByBathRoomCount($value)
    {
        return $this->filterThroughMapper('BathRoomCount', $value, 'filterByBathRoomCount');
    }

    /**
     * Filters by the number of store rooms, filter[anzahl_abstellraum_von] and filter[anzahl_abstellraum_bis]
     *
     * @param array{min?: int, max?: int} $value
     *
     * @return $this
     */
    public function filterByStoreRoomCount($value)
    {
        return $this->filterThroughMapper('StoreRoomCount', $value, 'filterByStoreRoomCount');
    }

    /**
     * Filters by disability access, filter[barrierefrei]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByDisabilityAccess($value)
    {
        return $this->filterThroughMapper('DisabilityAccess', $value, 'filterByDisabilityAccess');
    }

    /**
     * Filters by the condition, filter[zustand_id]
     *
     * @param int $value the id of the condition
     *
     * @return $this
     */
    public function filterByCondition($value)
    {
        return $this->filterThroughMapper('Condition', $value, 'filterByCondition');
    }

    /**
     * Filters by a keyword, filter[stichwort]
     *
     * @param string $value
     *
     * @return $this
     */
    public function filterByKeyword($value)
    {
        return $this->filterThroughMapper('Keyword', $value, 'filterByKeyword');
    }

    /**
     * Filters by the federal state, filter[bundesland_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByFederalStateId($value)
    {
        return $this->filterThroughMapper('FederalStateId', $value, 'filterByFederalStateId');
    }

    /**
     * Filters by the status of the realty, filter[objekt_status_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByStatusId($value)
    {
        return $this->filterThroughMapper('StatusId', $value, 'filterByStatusId');
    }

    /**
     * Filters by whether the realty is for rent, filter[miete]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByRent($value)
    {
        return $this->filterThroughMapper('Rent', $value, 'filterByRent');
    }

    /**
     * Filters by whether the realty is for sale, filter[kauf]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByBuy($value)
    {
        return $this->filterThroughMapper('Buy', $value, 'filterByBuy');
    }

    /**
     * Filters by the realty system type, filter[realty_type]
     *
     * @param string $value
     *
     * @return $this
     */
    public function filterByRealtySystemType($value)
    {
        return $this->filterThroughMapper('RealtySystemType', $value, 'filterByRealtySystemType');
    }

    /**
     * Filters by the parent realty, filter[parent_id]
     *
     * @param int $value the id of the parent realty
     *
     * @return $this
     */
    public function filterByParentId($value)
    {
        return $this->filterThroughMapper('ParentId', $value, 'filterByParentId');
    }

    /**
     * Filters by the price per square metre, filter[price_per_m2_von] and filter[price_per_m2_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByRentPerSqm($value)
    {
        return $this->filterThroughMapper('RentPerSqm', $value, 'filterByRentPerSqm');
    }

    /**
     * Filters by the date of the last update, filter[aktualisiert_am_von] and filter[aktualisiert_am_bis]
     *
     * @param array{min?: string, max?: string} $value dates, for example 2026-01-31
     *
     * @return $this
     */
    public function filterByUpdatedAt($value)
    {
        return $this->filterThroughMapper('UpdatedAt', $value, 'filterByUpdatedAt');
    }

    /**
     * Filters by a rent with a purchase option, filter[rent_with_purchase_option]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByRentWithPurchaseOption($value)
    {
        return $this->filterThroughMapper('RentWithPurchaseOption', $value, 'filterByRentWithPurchaseOption');
    }

    /**
     * Filters by the political district, filter[politischer_bezirk_id]
     *
     * @param int $value the id of the political district
     *
     * @return $this
     */
    public function filterByPoliticalDistrictId($value)
    {
        return $this->filterThroughMapper('PoliticalDistrictId', $value, 'filterByPoliticalDistrictId');
    }

    /**
     * Filters by the country, filter[land_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByCountryId($value)
    {
        return $this->filterThroughMapper('CountryId', $value, 'filterByCountryId');
    }

    /**
     * Filters by the owner, filter[owner_id]
     *
     * @param int $value the id of the owner
     *
     * @return $this
     */
    public function filterByOwnerId($value)
    {
        return $this->filterThroughMapper('OwnerId', $value, 'filterByOwnerId');
    }

    /**
     * Filters by the project the realty belongs to, filter[projekt_id]
     *
     * @param int $value the id of the project
     *
     * @return $this
     */
    public function filterByProjectId($value)
    {
        return $this->filterThroughMapper('ProjectId', $value, 'filterByProjectId');
    }

    /**
     * Sorts by the price, orderby=preis
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByPrice($direction = 'asc')
    {
        return $this->orderThroughMapper('Price', $direction);
    }

    /**
     * Sorts by the property number, orderby=objektnummer
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByPropertyNumber($direction = 'asc')
    {
        return $this->orderThroughMapper('PropertyNumber', $direction);
    }

    /**
     * Sorts by the date the realty was created, orderby=created_at
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByCreatedAt($direction = 'asc')
    {
        return $this->orderThroughMapper('CreatedAt', $direction);
    }

    /**
     * Sorts by the date the realty was published, orderby=published_at
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByPublishedAt($direction = 'asc')
    {
        return $this->orderThroughMapper('PublishedAt', $direction);
    }

    /**
     * Sorts by the zip code, orderby=plz
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByZipCode($direction = 'asc')
    {
        return $this->orderThroughMapper('ZipCode', $direction);
    }

    /**
     * The api sorts by updated_at alone, whatever the mapper calls the column
     *
     * RealtyMapper::getOrderMapping() names it too, but a mapper from outside the SDK may only know
     * its filter names, and the filter is aktualisiert_am. So the column is named here.
     *
     * @param string $direction
     *
     * @return $this
     */
    public function orderByUpdatedAt($direction = 'asc')
    {
        return $this->setOrder('updated_at', $direction);
    }

    /**
     * Return all project realties regardless of realty state (active, inactive, draft,...)
     *
     * @param bool $all
     *
     * @return $this
     */
    public function allProjectRealties($all = true)
    {
        return $this->setParameter('alleProjektObjekte', (int) $all);
    }

    /**
     * Search commercial realties by usable area of the sub realties
     *
     * @param bool $all
     *
     * @return $this
     */
    public function preciseAreaSearch($all = true)
    {
        return $this->setParameter('preciseAreaSearch', (int) $all);
    }

    /**
     * The api accepts a list here, and filterByRealtyCategory() builds the same wire filter, so
     * both calls add to it instead of the second discarding the first
     *
     * @param string|array<int, string> $value
     *
     * @return $this
     */
    public function filterByTag($value)
    {
        return $this->appendFilter($this->mapper->getFilterPropertyName('Tag'), $value);
    }

    /**
     * Filters by the date a realty was created.
     *
     * The api has no filter[created_at] and never had one, so this has always been discarded and
     * the search came back unfiltered. Strict mode rejects it.
     *
     * @deprecated The api has no such filter. There is no replacement; filter by
     *             filterByUpdatedAt() if a date range is what you need. Removed in 2.x
     *
     * @param string|array<string, mixed> $value
     *
     * @return $this
     */
    public function filterByCreatedAt($value)
    {
        $this->triggerDeprecation('RealtyQuery::filterByCreatedAt() is deprecated and is removed in 2.0.0. The api has no created_at filter, so it has never filtered.');

        return $this->filterThroughMapper('CreatedAt', $value, 'filterByCreatedAt');
    }

    /**
     * An alias of filterByTag(), both build filter[tag_name]. The api has no realty category
     * filter of its own, so this name says something the api does not have.
     *
     * @deprecated Use filterByTag(). Both build filter[tag_name]; this name suggests a filter
     *             which does not exist. Removed in 2.x
     *
     * @param string|array<int, string> $value
     *
     * @return $this
     */
    public function filterByRealtyCategory($value)
    {
        $this->triggerDeprecation('filterByRealtyCategory() is deprecated and is removed in 2.0.0. Use filterByTag(), which builds the same filter.');

        return $this->appendFilter($this->mapper->getFilterPropertyName('RealtyCategory'), $value);
    }

    /**
     * The api requires a list here and rejects a scalar with a 422, so a single value is wrapped
     *
     * @param string|int|array<int, string|int> $value
     *
     * @return $this
     */
    public function filterByEquipment($value)
    {
        return $this->applyFilter(
            $this->mapper->getFilterPropertyName('Equipment'),
            is_array($value) ? $value : [$value]
        );
    }
}
