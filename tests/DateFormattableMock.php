<?php

namespace Justimmo\Api\Tests;

use Justimmo\Api\Entity\Traits\DateFormatable;

class DateFormattableMock
{
    use DateFormatable;

    public function execFormatDate($value = null, $format = null)
    {
        return $this->formatDate($value, $format);
    }
}
