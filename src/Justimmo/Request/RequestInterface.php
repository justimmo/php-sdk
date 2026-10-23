<?php

namespace Justimmo\Request;

interface RequestInterface
{
    /**
     * Sends the request to the api.
     *
     * @return void
     */
    public function send();
}
