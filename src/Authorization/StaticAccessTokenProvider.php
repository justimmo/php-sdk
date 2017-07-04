<?php

namespace Justimmo\Api\Authorization;

class StaticAccessTokenProvider implements AccessTokenProvider
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * StaticAccessTokenProvider constructor.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @inheritDoc
     */
    public function refreshAccessToken()
    {
        return $this->getAccessToken();
    }
}
