<?php

namespace Justimmo\Model\Mapper;

/**
 * Interface SupportedParametersInterface
 *
 * What the api officially accepts for a given query call, so strict mode can ask instanceof instead
 * of method_exists()
 *
 * @package Justimmo\Model\Mapper
 */
interface SupportedParametersInterface
{
    /**
     * The filters the call accepts, keyed by api filter name without brackets and without a
     * _von or _bis suffix
     *
     * Each entry carries "forms", "level", "type" and optionally "list", "list_only" and "enum". An
     * unknown call returns an empty array.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getSupportedFilters(string $call);

    /**
     * Everything the call accepts that is not a filter, keyed by name
     *
     * The orderby enum and the picture sizes are in here like any other parameter.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getSupportedParameters(string $call);

    /**
     * The spec version the supported parameters were generated from
     *
     * @return string
     */
    public function getSpecVersion();
}
