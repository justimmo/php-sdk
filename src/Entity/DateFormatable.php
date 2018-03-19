<?php

namespace Justimmo\Api\Entity;

trait DateFormatable
{
    /**
     * @param \DateTime $dateTime
     * @param string    $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    protected function formatDate(\DateTime $dateTime = null, $format = null)
    {
        if (empty($dateTime)) {
            return null;
        }

        return $format === null
            ? $dateTime
            : $dateTime->format($format);
    }
}
