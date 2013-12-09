<?php

namespace Justimmo\Api;

use Justimmo\Cache\CacheInterface;
use Justimmo\Curl\CurlRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Class JustimmoApi
 * class for justimmo api
 *
 * @package Justimmo\Api
 */
class JustimmoApi implements JustimmoApiInterface
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
     * @param CacheInterface  $cache
     * @param string          $version
     */
    public function __construct($username, $password, LoggerInterface $logger, CacheInterface $cache, $version = 'v1')
    {
        $this
            ->setLogger($logger)
            ->setCache($cache)
            ->setUsername($username)
            ->setPassword($password)
            ->setVersion($version);
    }


    /**
     * makes a call to objekt list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callObjektList(array $params = array())
    {
        return $this->call('objekt/list', $params);
    }

    /**
     * @param makes a call to the justimmo api
     *
     * @param $url
     * @param array $params
     *
     * @return mixed
     */
    protected function call($url, array $params = array())
    {
        $url = $this->baseUrl . '/' . $this->version . '/' . $url;
        $this->logger->debug('begin api call - ' . $url);

        $key = $this->generateCacheKey($url, $params);
        $this->logger->debug('cache key is ' . $key);
        $content = $this->cache->get($key);
        if ($content !== false) {
            $this->logger->debug('cache found');
            $this->logger->debug($content);

            return $content;
        }

        $this->logger->debug('cache not found');

        if (count($params) > 0) {
            $queryString = http_build_query($params);
            $queryString = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $queryString);
            $url .= '?' . $queryString;
        }

        $this->logger->debug('call api: ' . $url);
        $request = new CurlRequest($url, array(
            CURLOPT_USERPWD        => $this->username . ':' . $this->password,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPAUTH       => CURLAUTH_ANY
        ));

        $response = $request->get();
        $this->logger->debug($response);

        if ($request->getError()) {
            $this->throwError('The Api call returned an error ' . $request->getError());
        }

        if ($request->getStatusCode() != 200) {
            $this->throwError('The Api call returned status code ' . $request->getStatusCode());
        }

        $this->cache->set($key, $response);

        return $response;
    }

    /**
     * generates a cache key for the api call
     *
     * @param $url
     * @param array $params
     *
     * @return string
     */
    protected function generateCacheKey($url, array $params = array())
    {
        return 'ji_api_' . sha1($url . http_build_query($params));
    }

    /**
     * throws and logs an error
     *
     * @param $message
     *
     * @throws \InvalidArgumentException
     */
    protected function throwError($message)
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
        if (mb_strlen($password) == 0) {
            $this->throwError('Password not set');
        }

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
        if (mb_strlen($username) == 0) {
            $this->throwError('Username not set');
        }

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
        if (!in_array($version, $this->supportedVersions)) {
            $this->throwError('The version ' . $version . ' is not supported by this library');
        }

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
