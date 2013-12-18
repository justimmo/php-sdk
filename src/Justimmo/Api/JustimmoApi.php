<?php

namespace Justimmo\Api;

use Justimmo\Cache\CacheInterface;
use Justimmo\Curl\CurlRequest;
use Psr\Log\LoggerInterface;

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
     * makes a call to Realty list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callRealtyList(array $params = array())
    {
        return $this->call('objekt/list', $params);
    }

    /**
     * calls the detail information of a single realty
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callRealtyDetail($pk)
    {
        return $this->call('objekt/detail', array('objekt_id' => $pk));
    }

    /**
     * calls the detail information of a single employee
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callEmployeeDetail($pk)
    {
        return $this->call('team/detail', array('id' => $pk));
    }

    /**
     * calls the detail information of a single project
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callProjectDetail($pk)
    {
        return $this->call('projekt/detail', array('id' => $pk));
    }


    /**
     * make a call to the team list with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callEmployeeList(array $params = array())
    {
        return $this->call('team/list', $params);
    }

    /**
     * make a call to the project list with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callProjectList(array $params = array())
    {
        return $this->call('projekt/list', $params);
    }

    /**
     * generates a url for an api request
     *
     * @param       $call
     * @param array $params
     *
     * @return string
     */
    public function generateUrl($call, array $params = array())
    {
        $url = $this->baseUrl . '/' . $this->version . '/' . $call;
        if (count($params) > 0) {
            $queryString = http_build_query($params);
            $queryString = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $queryString);
            $url .= '?' . $queryString;
        }

        return $url;
    }


    /**
     * @param makes a call to the justimmo api
     *
     * @param $call
     * @param array $params
     *
     * @return mixed
     */
    protected function call($call, array $params = array())
    {
        $url = $this->generateUrl($call, $params);

        $this->logger->debug('begin api call - ' . $url);

        $key = $this->cache->generateCacheKey($url);
        $this->logger->debug('cache key is ' . $key);
        $content = $this->cache->get($key);
        if ($content !== false) {
            $this->logger->debug('cache found');
            $this->logger->debug($content);

            return $content;
        }

        $this->logger->debug('cache not found');

        $this->logger->debug('call api: ' . $url);
        $request = new CurlRequest($url, array(
            CURLOPT_USERPWD        => $this->username . ':' . $this->password,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPAUTH       => CURLAUTH_ANY
        ));

        $response = $request->get();
        $this->logger->debug($response);

        if ($request->getError()) {
            $this->throwError('The Api call returned an error: "' . $request->getError() . '"');
        }

        if ($request->getStatusCode() == 401) {
            $this->throwError('Bad Username / Password ' . $request->getStatusCode(), '\Justimmo\Exception\AuthenticationException');
        }

        if ($request->getStatusCode() == 404) {
            $this->throwError('Api call not found: ' . $request->getStatusCode(), '\Justimmo\Exception\NotFoundException');
        }

        if ($request->getStatusCode() != 200) {
            $this->throwError('The Api call returned status code ' . $request->getStatusCode(), '\Justimmo\Exception\StatusCodeException');
        }

        $this->cache->set($key, $response);

        return $response;
    }

    /**
     * throws and logs an error
     *
     * @param $message
     * @param string $exceptionClass
     *
     * @throws \InvalidArgumentException
     */
    protected function throwError($message, $exceptionClass = '\InvalidArgumentException')
    {
        $this->logger->error($message);
        throw new $exceptionClass($message);
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
