<?php

/*
 * This file is generated. Do not edit it.
 *
 * Source:    data/sdk-supported-parameters.json, spec 1.2.20
 * Generator: bin/generate-supported-parameters.php
 *
 * Run `php bin/generate-supported-parameters.php --check` to find out whether the two have drifted.
 */

namespace Justimmo\Model\Mapper\V1;

/**
 * Class SupportedParameters
 *
 * What the Website API officially accepts, per query call. Data only, no behaviour
 *
 * @package Justimmo\Model\Mapper\V1
 */
final class SupportedParameters
{
    /**
     * The spec version this data was generated from
     */
    const SPEC_VERSION = '1.2.20';

    /**
     * Changes when the accepted parameters change, not when a description is reworded
     */
    const SPEC_HASH = 'sha256:82bdae5053236e66c27a65ec8556538b07a48f110eb4d9b4d11495288c1a0a71';

    /**
     * The filters each call accepts, keyed by the api filter name without brackets and without a
     * _von or _bis suffix
     *
     * "forms" says which of filter[x], filter[x_von] and filter[x_bis] are allowed. "list" and
     * "enum" describe the plain form, because a range form is always a scalar. "level" decides
     * whether alle=1 voids the filter.
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
        'callProjectDetail' => [],
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
        'callEmployeeList' => [],
        'callEmployeeDetail' => [],
        'callEmployeeIds' => [],
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
            'showDetails' => [
                'in' => 'path',
                'type' => 'integer',
                'enum' => [0, 1],
                'default' => 0,
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
        'callEmployeeList' => [],
        'callEmployeeDetail' => [
            'id' => [
                'in' => 'operation',
                'type' => 'integer',
            ],
        ],
        'callEmployeeIds' => [],
    ];
}
