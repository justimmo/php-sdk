<?php

namespace Justimmo\Api\Authorization;

final class StaticAccessTokenProvider implements AccessTokenProvider
{
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @inheritDoc
     */
    public function refreshAccessToken(): string
    {
        return $this->getAccessToken();
    }
}
