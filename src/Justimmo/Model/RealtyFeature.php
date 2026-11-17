<?php declare(strict_types=1);

namespace Justimmo\Model;

/**
 * A feature a realty can have, like a floor heating, and the group it belongs to
 *
 * The list includes the features the account defined itself, so it differs per account. Such a
 * feature may have no code.
 *
 * @immutable
 */
final class RealtyFeature
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $code,
        public readonly int    $groupId,
        public readonly string $groupName,
        public readonly string $groupCode,
    ) {
    }
}
