<?php

namespace Justimmo\Api\Entity\Traits;

trait DateFormatable
{
    /**
     * @param \DateTime $dateTime
     * @param string    $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    protected function formatDate(\DateTime $dateTime, $format = null)
    {
        if ($dateTime === null) {
            return null;
        }

        return $format === null
            ? $dateTime
            : $dateTime->format($format);
    }
}
