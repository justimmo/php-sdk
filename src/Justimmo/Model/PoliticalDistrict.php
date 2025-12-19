<?php declare(strict_types=1);

namespace Justimmo\Model;

/**
 * @immutable
 */
final class PoliticalDistrict
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
    ) {
    }
}
