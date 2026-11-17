<?php

/*
 * This file is generated. Do not edit it.
 *
 * Source:    data/sdk-supported-parameters.json, spec 1.2.21
 * Generator: bin/generate-supported-parameters.php
 *
 * Run `php bin/generate-supported-parameters.php --check` to find out whether the two have drifted.
 */

namespace Justimmo\Model\Mapper\V1;

/**
 * Class SupportedParameters
 *
 * What the Website API officially accepts, per SDK method of JustimmoApi. Data only, no behaviour
 *
 * @package Justimmo\Model\Mapper\V1
 */
final class SupportedParameters
{
    /**
     * The spec version this data was generated from
     */
    const SPEC_VERSION = '1.2.21';

    /**
     * Changes when the accepted parameters change, not when a description is reworded
     */
    const SPEC_HASH = 'sha256:2a07461b13c5d2c33ca47a12a50cc9bc6d05c844fbb212ec909e3bf296d24769';

    /**
     * The filters each call accepts, keyed by the api filter name without brackets and without a
     * _von or _bis suffix
     *
     * "forms" says which of filter[x], filter[x_von] and filter[x_bis] are allowed. "type" is the
     * type of one value. "list", "list_only" and "enum" describe the plain form, because a range
     * form is always a scalar: "list" allows a list, "list_only" allows nothing else. "level"
     * decides whether alle=1 voids the filter.
     */
    const FILTERS = [
        'callRealtyList' => [
            'aktualisiert_am' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'anzahl_abstellraum' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_badezimmer' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_balkons' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_garagen' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_garten' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_keller' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_loggias' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_sep_wc' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_stellplaetze' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_terrassen' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'barrierefrei' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'bauart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [1, 2],
            ],
            'bundesland_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'flaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'grundflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'kauf' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'land_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'miete' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'nutzflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'objekt_ausstattung_list' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
                'list_only' => true,
            ],
            'objekt_status_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektnummer' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'owner_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'parent_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'plz' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'politischer_bezirk_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'preis' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'price_per_m2' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'projekt_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'realty_type' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'rent_with_purchase_option' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'sort_flaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'stichwort' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'subobjektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'tag_name' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'wohnflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'zimmer' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'zustand_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
        ],
        'callRealtyIds' => [
            'aktualisiert_am' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'anzahl_abstellraum' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_badezimmer' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_balkons' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_garagen' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_garten' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_keller' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_loggias' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_sep_wc' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_stellplaetze' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'anzahl_terrassen' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'barrierefrei' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'bauart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [1, 2],
            ],
            'bundesland_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'flaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'grundflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'kauf' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'land_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'miete' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'nutzflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'objekt_ausstattung_list' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
                'list_only' => true,
            ],
            'objekt_status_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektnummer' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'owner_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'parent_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'plz' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'politischer_bezirk_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'preis' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'price_per_m2' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'projekt_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'realty_type' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'rent_with_purchase_option' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'sort_flaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'stichwort' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'subobjektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'tag_name' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'wohnflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'zimmer' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'zustand_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
        ],
        'callRealtyDetail' => [],
        'callExpose' => [],
        'postRealtyInquiry' => [],
        'callProjectList' => [
            'bundesland_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'fertigstellung' => [
                'forms' => ['range'],
                'level' => 'project',
                'type' => 'string',
            ],
            'flaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'grundflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'kauf' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'miete' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'nutzflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'objekt_status_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektnummer' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'owner_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'plz' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'preis' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'project_tag_name' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'string',
                'list' => true,
            ],
            'projekt_objektnummer' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'string',
            ],
            'projekt_status' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'string',
                'list' => true,
                'enum' => ['planning', 'building', 'finished'],
            ],
            'referenz' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'stichwort' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'subobjektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'tag_name' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'verkaufsstart' => [
                'forms' => ['range'],
                'level' => 'project',
                'type' => 'string',
            ],
            'wohnflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'zimmer' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
        ],
        'callProjectShortList' => [],
        'callProjectIds' => [
            'bundesland_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'fertigstellung' => [
                'forms' => ['range'],
                'level' => 'project',
                'type' => 'string',
            ],
            'flaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'grundflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'kauf' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'miete' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'nutzflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'objekt_status_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'objektnummer' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'owner_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
            ],
            'plz' => [
                'forms' => ['plain', 'range'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'preis' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'project_tag_name' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'string',
                'list' => true,
            ],
            'projekt_objektnummer' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'string',
            ],
            'projekt_status' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'string',
                'list' => true,
                'enum' => ['planning', 'building', 'finished'],
            ],
            'referenz' => [
                'forms' => ['plain'],
                'level' => 'project',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'stichwort' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
            ],
            'subobjektart_id' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'integer',
                'list' => true,
            ],
            'tag_name' => [
                'forms' => ['plain'],
                'level' => 'realty',
                'type' => 'string',
                'list' => true,
            ],
            'verkaufsstart' => [
                'forms' => ['range'],
                'level' => 'project',
                'type' => 'string',
            ],
            'wohnflaeche' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
            'zimmer' => [
                'forms' => ['range'],
                'level' => 'realty',
                'type' => 'number',
            ],
        ],
        'callProjectDetail' => [],
        'callCountries' => [],
        'callFederalStates' => [],
        'callPoliticalDistricts' => [],
        'callRealtyTypes' => [],
        'callRealtyCategories' => [],
        'callRealtyFeatures' => [],
        'callZipCodes' => [],
        'callRegions' => [],
        'callEmployeeList' => [],
        'callEmployeeIds' => [],
        'callEmployeeDetail' => [],
        'callTenant' => [],
    ];

    /**
     * Everything each call accepts that is not a filter, keyed by name
     *
     * "in" says which level of the spec declared it. The orderby enum and the picture sizes are in
     * here like any other parameter.
     */
    const PARAMETERS = [
        'callRealtyList' => [
            'alleProjektObjekte' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'culture' => [
                'in' => 'path',
                'type' => 'string',
                'default' => 'de',
            ],
            'limit' => [
                'in' => 'path',
                'type' => 'integer',
                'default' => 10,
            ],
            'offset' => [
                'in' => 'path',
                'type' => 'integer',
                'default' => 0,
            ],
            'orderby' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['created_at', 'objektnummer', 'plz', 'preis', 'published_at', 'updated_at'],
            ],
            'ordertype' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
                'case_insensitive' => true,
            ],
            'picturesize' => [
                'in' => 'path',
                'type' => 'string',
                'list' => true,
                'enum' => [
                    'small',
                    's220x155',
                    's312x208',
                    'medium',
                    'medium_unbranded',
                    'big',
                    'big_unbranded',
                    'big2',
                    'big2_unbranded',
                    'fullhd',
                    'fullhd_unbranded',
                    'fullhd_downscale',
                    'fullhd_unbranded_downscale',
                    'uhd',
                    'uhd_unbranded',
                    'uhd_downscale',
                    'uhd_unbranded_downscale',
                    'orig',
                ],
                'default' => 'orig',
            ],
            'preciseAreaSearch' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'showDetails' => [
                'in' => 'path',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
        ],
        'callRealtyIds' => [
            'alleProjektObjekte' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'orderby' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['created_at', 'objektnummer', 'plz', 'preis', 'published_at', 'updated_at'],
            ],
            'ordertype' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
                'case_insensitive' => true,
            ],
            'preciseAreaSearch' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
        ],
        'callRealtyDetail' => [
            'culture' => [
                'in' => 'operation',
                'type' => 'string',
                'default' => 'de',
            ],
            'objekt_id' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
            'picturesize' => [
                'in' => 'operation',
                'type' => 'string',
                'list' => true,
                'enum' => [
                    'small',
                    's220x155',
                    's312x208',
                    'medium',
                    'medium_unbranded',
                    'big',
                    'big_unbranded',
                    'big2',
                    'big2_unbranded',
                    'fullhd',
                    'fullhd_unbranded',
                    'fullhd_downscale',
                    'fullhd_unbranded_downscale',
                    'uhd',
                    'uhd_unbranded',
                    'uhd_downscale',
                    'uhd_unbranded_downscale',
                    'orig',
                ],
                'default' => 'orig',
            ],
        ],
        'callExpose' => [
            'culture' => [
                'in' => 'operation',
                'type' => 'string',
                'default' => 'de',
            ],
            'expose' => [
                'in' => 'operation',
                'type' => 'string',
                'default' => 'Default',
            ],
            'objekt_id' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
        ],
        'postRealtyInquiry' => [
            'anrede_id' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [1, 2],
            ],
            'doubleoptin_completed' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'email' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'firma' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'funding_inquiry' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'is_realty_owner' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'kategorie' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'land' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
            'message' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'nachname' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'newsletter' => [
                'in' => 'operation',
                'type' => 'integer',
                'list' => true,
                'list_only' => true,
            ],
            'objekt_id' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
            'ort' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'plz' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'realty_owner_consultation_request' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
            ],
            'strasse' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'suffix' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'tel' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'titel' => [
                'in' => 'operation',
                'type' => 'string',
            ],
            'vorname' => [
                'in' => 'operation',
                'type' => 'string',
            ],
        ],
        'callProjectList' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'alleProjektObjekte' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'culture' => [
                'in' => 'path',
                'type' => 'string',
                'default' => 'de',
            ],
            'limit' => [
                'in' => 'path',
                'type' => 'integer',
                'default' => 25,
            ],
            'objektIds' => [
                'in' => 'path',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'offset' => [
                'in' => 'path',
                'type' => 'integer',
                'default' => 0,
            ],
            'orderby' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['created_at', 'updated_at'],
            ],
            'ordertype' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
                'case_insensitive' => true,
            ],
            'picturesize' => [
                'in' => 'path',
                'type' => 'string',
                'list' => true,
                'enum' => [
                    'small',
                    's220x155',
                    's312x208',
                    'medium',
                    'medium_unbranded',
                    'big',
                    'big_unbranded',
                    'big2',
                    'big2_unbranded',
                    'fullhd',
                    'fullhd_unbranded',
                    'fullhd_downscale',
                    'fullhd_unbranded_downscale',
                    'uhd',
                    'uhd_unbranded',
                    'uhd_downscale',
                    'uhd_unbranded_downscale',
                    'orig',
                ],
                'default' => 'orig',
            ],
        ],
        'callProjectShortList' => [],
        'callProjectIds' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'alleProjektObjekte' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'orderby' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['created_at', 'updated_at'],
            ],
            'ordertype' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['asc', 'desc'],
                'default' => 'asc',
                'case_insensitive' => true,
            ],
        ],
        'callProjectDetail' => [
            'alleProjektObjekte' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'culture' => [
                'in' => 'operation',
                'type' => 'string',
                'default' => 'de',
            ],
            'id' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
            'objektIds' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'picturesize' => [
                'in' => 'operation',
                'type' => 'string',
                'list' => true,
                'enum' => [
                    'small',
                    's220x155',
                    's312x208',
                    'medium',
                    'medium_unbranded',
                    'big',
                    'big_unbranded',
                    'big2',
                    'big2_unbranded',
                    'fullhd',
                    'fullhd_unbranded',
                    'fullhd_downscale',
                    'fullhd_unbranded_downscale',
                    'uhd',
                    'uhd_unbranded',
                    'uhd_downscale',
                    'uhd_unbranded_downscale',
                    'orig',
                ],
                'default' => 'orig',
            ],
        ],
        'callCountries' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
        ],
        'callFederalStates' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'land' => [
                'in' => 'operation',
                'type' => 'integer',
                'types' => ['integer', 'string'],
            ],
        ],
        'callPoliticalDistricts' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'bundesland' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
            'land' => [
                'in' => 'operation',
                'type' => 'string',
                'enum' => ['AT'],
                'default' => 'AT',
            ],
        ],
        'callRealtyTypes' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'culture' => [
                'in' => 'operation',
                'type' => 'string',
                'default' => 'de',
            ],
        ],
        'callRealtyCategories' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
        ],
        'callRealtyFeatures' => [
            'culture' => [
                'in' => 'operation',
                'type' => 'string',
                'default' => 'de',
            ],
            'gruppe' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
        ],
        'callZipCodes' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'bundesland' => [
                'in' => 'operation',
                'type' => 'integer',
                'types' => ['integer', 'string'],
            ],
            'land' => [
                'in' => 'operation',
                'type' => 'integer',
                'types' => ['integer', 'string'],
            ],
        ],
        'callRegions' => [
            'alle' => [
                'in' => 'operation',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
            ],
            'bundesland' => [
                'in' => 'operation',
                'type' => 'integer',
                'types' => ['integer', 'string'],
            ],
            'land' => [
                'in' => 'operation',
                'type' => 'integer',
                'types' => ['integer', 'string'],
            ],
        ],
        'callEmployeeList' => [],
        'callEmployeeIds' => [],
        'callEmployeeDetail' => [
            'id' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
        ],
        'callTenant' => [],
    ];

    /**
     * The names of the user-defined simple fields the api sends: fields Justimmo adds to the
     * OpenImmo standard, each sent with its name, sorted
     */
    const USER_DEFINED_SIMPLE_FIELDS = [
        'aktualisiert_am',
        'anzahl_garagen',
        'ausrichtung',
        'bauart_id',
        'befristete_flaeche',
        'belagsfertig',
        'epass_fgeeklasse',
        'epass_fgeewert',
        'epass_hwbklasse',
        'epass_hwbwert',
        'erstauftraggeberprinzip',
        'erstellt_am',
        'etage',
        'finanzierungsbeitrag',
        'geokoordinaten_breitengrad',
        'geokoordinaten_breitengrad_exakt',
        'geokoordinaten_laengengrad',
        'geokoordinaten_laengengrad_exakt',
        'gewichtete_flaeche',
        'grundbucheintragung',
        'grunderwerbssteuer',
        'hallenhoehe',
        'immocard_firma_id',
        'immocard_id',
        'ji_acquisition_state',
        'ji_anzeige_in_suchergebnis',
        'ji_completion_date',
        'ji_eigentumsform_id',
        'ji_is_reference',
        'ji_kundennummer',
        'ji_objektstatus',
        'ji_objektstatus_id',
        'ji_projekt_status',
        'ji_sale_start',
        'ji_stand_vom',
        'miete_ust',
        'mietkauf',
        'moeblierung',
        'monatliche_reparaturruecklage_brutto',
        'monatliche_reparaturruecklage_netto',
        'monatliche_reparaturruecklage_ust',
        'monatlichekostenbrutto',
        'objektart_id',
        'objektart_name',
        'parent_id',
        'politischer_bezirk',
        'politischer_bezirk_id',
        'projekt_url',
        'raumhoehe',
        'realty_system_type',
        'rohdachboden_flaeche',
        'schluesselfertig',
        'stiege',
        'sub_objektart_id',
        'sub_objektart_name',
        'ungenaue_verortung_breitengrad',
        'ungenaue_verortung_laengengrad',
        'ungenaue_verortung_radius',
        'url',
        'verbaubare_flaeche',
        'vermittelt_am',
        'vertragserrichtungsgebuehr',
        'wohnbaufoerderung',
    ];

    /**
     * The SDK methods whose endpoint the api marks deprecated, sorted. Each is deprecated in the SDK
     * too, with a notice
     */
    const DEPRECATED_CALLS = ['callRegions', 'callZipCodes'];
}
