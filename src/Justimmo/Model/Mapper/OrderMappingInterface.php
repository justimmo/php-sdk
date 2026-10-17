<?php declare(strict_types=1);

namespace Justimmo\Model\Mapper;

/**
 * Translates a model property name into the column the api sorts by.
 *
 * Sorting and filtering are different namespaces, and the api does not spell them the same way:
 * objekt filters by `aktualisiert_am` and sorts by `updated_at`. One mapping table serving both
 * sends a sort column the api has never had, which it answers by sorting the way it would have
 * anyway. This interface exists so a mapper can keep the two apart.
 *
 * It is separate from MapperInterface on purpose. That interface is published, so adding a method
 * to it would be a fatal error for every mapper written outside the SDK.
 */
interface OrderMappingInterface
{
    /**
     * Gets the api sort column for a model property name.
     *
     * An unknown name is returned unchanged, as the filter mapping does.
     */
    public function getOrderPropertyName(string $column): string;
}
