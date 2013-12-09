<?php

namespace Justimmo\Api;

use Justimmo\Cache\CacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Class JustimmoApi
 * class for justimmo api
 *
 * @package Justimmo\Api
 */
class JustimmoApi
{
    /**
     * api versions supported
     *
     * @var array
     */
    protected $supportedVersions = array('v1');

    /**
     * base url where the justimmo api is located
     *
     * @var string
     */
    protected $baseUrl = 'http://api.justimmo.at/rest';

    /**
     * version of api to be called
     *
     * @var string
     */
    protected $version = 'v1';

    /**
     * logger interface for logging
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * username of api access
     *
     * @var string
     */
    protected $username;

    /**
     * password for api access
     *
     * @var string
     */
    protected $password;

    /**
     * Cache class for caching results
     *
     * @var \Justimmo\Cache\CacheInterface
     */
    protected $cache;

    /**
     *
     * @param $username
     * @param $password
     * @param LoggerInterface $logger
     * @param CacheInterface $cache
     * @param string $version
     */
    public function __construct($username, $password, LoggerInterface $logger, CacheInterface $cache, $version = 'v1')
    {
        $this->logger = $logger;
        $this->cache = $cache;

        if (mb_strlen($username) == 0) {
            $this->throwError('Username not set');
        }

        if (mb_strlen($password) == 0) {
            $this->throwError('Password not set');
        }

        if (!in_array($version, $this->supportedVersions)) {
            $this->throwError('The version ' . $version . ' is not supported by this library');
        }

        $this->username = $username;
        $this->password = $password;
        $this->version  = $version;
    }

    /**
     * throws and logs an error
     *
     * @param $message
     *
     * @throws \InvalidArgumentException
     */
    private function throwError($message)
    {
        $this->logger->error($message);
        throw new \InvalidArgumentException($message);
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param \Justimmo\Cache\CacheInterface $cache
     *
     * @return $this
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
        return $this;
    }


}
