<?php

namespace Justimmo\Api\Request;

class ExposeRequest implements ApiRequest
{
    /**
     * @var int
     */
    protected $realtyId;

    /**
     * @var int
     */
    protected $exposeId;

    /**
     * ExposeRequest constructor.
     *
     * @param int $realtyId
     * @param int $exposeId
     */
    public function __construct($realtyId, $exposeId)
    {
        $this->realtyId = (int) $realtyId;
        $this->exposeId = (int) $exposeId;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return '/expose/' . $this->realtyId . '/' . $this->exposeId;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * @inheritDoc
     */
    public function getGuzzleOptions()
    {
        return [
            'timeout' => 60,
            'stream' => true,
        ];
    }
}
