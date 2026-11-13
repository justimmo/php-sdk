<?php

namespace Justimmo\Tests\Model;

use Justimmo\Model\TriggersDeprecations;

/**
 * A model with one method deprecated in 1.8.0
 *
 * A named class, not an anonymous one: PHP's internal name of an anonymous class without a parent
 * carries a NUL byte, and PHP 8.1 cuts the notice there.
 */
class DeprecatingModel
{
    use TriggersDeprecations;

    public function getOld(): void
    {
        $this->triggerDeprecation('getOld', 'getOld() is deprecated and is removed in 2.0.0. Use getNew().', '1.8.0');
    }
}
