<?php

namespace Justimmo\Api\Exception;


class NotFoundException extends RequestException
{
    public static function notFound(\Exception $previousException)
    {
        return new static('The resource has not been found.', 401, $previousException);
    }
}
