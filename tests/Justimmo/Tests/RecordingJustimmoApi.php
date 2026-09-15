<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoNullApi;

/**
 * Null api which records the parameters it has been called with, needed wherever the
 * query clears its parameters before the test can look at them
 */
class RecordingJustimmoApi extends JustimmoNullApi
{
    /**
     * @var array<string, mixed>|null
     */
    private ?array $lastParams = null;

    public function callRealtyList(array $params = array()): string
    {
        $this->lastParams = $params;

        return parent::callRealtyList($params);
    }

    /**
     * The parameters of the last list call, null if no call has been made
     *
     * @return array<string, mixed>|null
     */
    public function getLastParams(): ?array
    {
        return $this->lastParams;
    }
}
