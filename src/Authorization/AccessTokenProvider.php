<?php

namespace Justimmo\Api\Authorization;

interface AccessTokenProvider
{
    /**
     * Returns current access token
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * Returns a new access token. Called by client if provied access token has been revoked by api
     *
     * @return string
     */
    public function refreshAccessToken(): string;
}
