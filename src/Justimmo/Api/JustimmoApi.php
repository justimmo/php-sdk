<?php

namespace Justimmo\Api;

use Composer\InstalledVersions;
use Justimmo\Cache\CacheInterface;
use Justimmo\Cache\NullCache;
use Justimmo\Curl\CurlRequest;
use Justimmo\Exception\InvalidRequestException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
    protected $baseUrl = 'https://api.justimmo.at/rest';

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
     * culture for api calls to set if not explicetely set on call
     *
     * @var string
     */
    protected $culture = 'de';

    /**
     * options for php curl request
     *
     * @var array
     */
    protected $curlOptions = array(
        CURLOPT_CONNECTTIMEOUT_MS => 2500,
        CURLOPT_RETURNTRANSFER    => true,
        CURLOPT_SSL_VERIFYPEER    => false,
    );

    /**
     * installed version of this package, resolved once per process
     */
    private static ?string $sdkVersion = null;

    /**
     *
     * @param                 $username
     * @param                 $password
     * @param ?LoggerInterface $logger
     * @param ?CacheInterface  $cache
     * @param string          $version
     * @param string          $culture
     */
    public function __construct($username, $password, ?LoggerInterface $logger = null, ?CacheInterface $cache = null, $version = 'v1', $culture = 'de')
    {
        $this
            ->setLogger(($logger ?: new NullLogger()))
            ->setCache(($cache ?: new NullCache()))
            ->setCulture($culture)
            ->setUsername($username)
            ->setPassword($password)
            ->setVersion($version);
    }

    /**
     * @inheritdoc
     */
    public function callRealtyList(array $params = array())
    {
        $params['showDetails'] = 1;

        return $this->call('objekt/list', $params);
    }

    /**
     * @inheritdoc
     */
    public function callRealtyIds(array $params = array())
    {
        return $this->call('objekt/ids', $params);
    }

    /**
     * @inheritdoc
     */
    public function callRealtyDetail($pk, array $params = array())
    {
        $params['objekt_id'] = $pk;

        return $this->call('objekt/detail', $params);
    }

    /**
     * @inheritdoc
     */
    public function callEmployeeDetail($pk)
    {
        return $this->call('team/detail', array('id' => $pk));
    }

    /**
     * @inheritdoc
     */
    public function callProjectDetail($pk, array $params = array())
    {
        $params['id'] = $pk;

        return $this->call('projekt/detail', $params);
    }

    /**
     * @inheritdoc
     */
    public function callEmployeeList(array $params = array())
    {
        return $this->call('team/list', $params);
    }

    /**
     * @inheritdoc
     */
    public function callProjectList(array $params = array())
    {
        return $this->call('projekt/list', $params);
    }

    /**
     * @inheritdoc
     */
    public function callCountries(array $params = array())
    {
        return $this->call('objekt/laender', $params);
    }

    /**
     * @inheritdoc
     */
    public function callFederalStates(array $params = array())
    {
        return $this->call('objekt/bundeslaender', $params);
    }

    /**
     * @inheritdoc
     */
    public function callZipCodes(array $params = array())
    {
        return $this->call('objekt/plzsUndOrte', $params);
    }

    /**
     * @inheritdoc
     *
     * @deprecated - This call is deprecated and will be removed in 2.x. Use callPoliticalDistricts instead.
     */
    public function callRegions(array $params = array())
    {
        return $this->call('objekt/regionen', $params);
    }

    /**
     * @inheritdoc
     */
    public function callPoliticalDistricts(array $params = []): string
    {
        return $this->call('objekt/politischeBezirke', $params);
    }

    /**
     * @inheritdoc
     */
    public function callRealtyTypes(array $params = array())
    {
        return $this->call('objekt/objektarten', $params);
    }

    /**
     * @inheritdoc
     */
    public function callRealtyCategories(array $params = array())
    {
        return $this->call('objekt/kategorien', $params);
    }

    /**
     * @inheritdoc
     */
    public function callTenant(array $params = array())
    {
        return $this->call('main/tenant', $params);
    }

    /**
     * @inheritdoc
     */
    public function callExpose($pk, $type = 'Default')
    {
        return $this->call('objekt/expose', array('objekt_id' => $pk, 'expose' => $type));
    }

    /**
     * @inheritdoc
     */
    public function postRealtyInquiry(array $params = array())
    {
        return $this->call('objekt/anfrage', $params);
    }

    /**
     * @inheritdoc
     */
    public function callEmployeeIds(array $params = array())
    {
        return $this->call('team/ids', $params);
    }

    /**
     * @inheritdoc
     */
    public function callProjectIds(array $params = array())
    {
        return $this->call('projekt/ids', $params);
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
            $queryString = http_build_query($params, '', '&');
            $queryString = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $queryString);
            $url .= '?' . $queryString;
        }

        return $url;
    }

    /**
     * Makes a call to the justimmo api
     *
     * @param $call
     * @param array $params
     *
     * @return mixed
     */
    public function call($call, array $params = array())
    {
        $startTime = microtime(true);

        if (!array_key_exists('culture', $params)) {
            $params['culture'] = $this->culture;
        }

        $url = $this->generateUrl($call, $params);
        $this->logger->debug('call start', array(
            'url'      => $url,
        ));

        $key = $this->cache->generateCacheKey($url);
        $content = $this->cache->get($key);
        if ($content !== false) {
            $this->logger->debug('call end', array(
                'url'      => $url,
                'cache'    => true,
                'time'     => microtime(true) - $startTime,
                'response' => $content,
            ));

            return $content;
        }

        $request = $this->createRequest($url);

        if (!ini_get('open_basedir') && filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN) === false) {
            $request->setOption(CURLOPT_FOLLOWLOCATION, true);
        }

        $response = $request->get();

        if ($request->getError()) {
            $this->throwError('The Api call returned an error: "' . $request->getError() . '"');
        }

        if ($request->getStatusCode() == 401) {
            $this->throwError('Bad Username / Password ' . $request->getStatusCode(), '\Justimmo\Exception\AuthenticationException');
        }

        if ($request->getStatusCode() == 404) {
            $this->throwError('Api call not found: ' . $request->getStatusCode(), '\Justimmo\Exception\NotFoundException');
        }

        if ($request->getStatusCode() >= 400 && $request->getStatusCode() < 500) {
            $exception = new InvalidRequestException('The Api call returned status code ' . $request->getStatusCode());
            $exception->setResponse($request->getContent());
            $this->logger->error($exception->getMessage());

            throw $exception;
        }

        if ($request->getStatusCode() != 200) {
            $this->throwError('The Api call returned status code ' . $request->getStatusCode(), '\Justimmo\Exception\StatusCodeException');
        }

        $this->cache->set($key, $response);

        $this->logger->debug('call end', array(
            'url'      => $url,
            'cache'    => false,
            'time'     => microtime(true) - $startTime,
            'response' => $response,
        ));

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

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCulture($value)
    {
        $this->culture = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @param array $curlOptions
     *
     * @return $this
     */
    public function setCurlOptions($curlOptions)
    {
        $this->curlOptions = $curlOptions;

        return $this;
    }

    /**
     * sets a specific option for curl requests
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setCurlOption($key, $value)
    {
        $this->curlOptions[$key] = $value;

        return $this;
    }

    /**
     * The installed version of this package, "unknown" if it cannot be determined,
     * eg. when the sdk is not installed with composer
     */
    private static function getSdkVersion(): string
    {
        if (self::$sdkVersion === null) {
            $version = null;

            if (class_exists(InstalledVersions::class)
                && InstalledVersions::isInstalled('justimmo/php-sdk')
            ) {
                $version = InstalledVersions::getPrettyVersion('justimmo/php-sdk');
            }

            self::$sdkVersion = $version ?? 'unknown';
        }

        return self::$sdkVersion;
    }

    protected function createRequest($url)
    {
        // requests of the sdk carry their own headers so they can be told apart from
        // handwritten api calls, headers of the integrator are kept
        $headers = [];
        if (isset($this->curlOptions[CURLOPT_HTTPHEADER]) && is_array($this->curlOptions[CURLOPT_HTTPHEADER])) {
            $headers = $this->curlOptions[CURLOPT_HTTPHEADER];
        }

        $headers[] = 'X-Justimmo-PHP-SDK-Version: ' . self::getSdkVersion();

        // an agent can be set through either option, and curl lets a User-Agent header win over
        // CURLOPT_USERAGENT, so an agent in the header list gets the token appended there
        foreach ($headers as $index => $header) {
            if (!is_string($header) || stripos($header, 'user-agent:') !== 0) {
                continue;
            }

            $headerAgent = trim(substr($header, strlen('user-agent:')));

            // a header without a value removes it in curl, which is a deliberate choice and is
            // left alone. Such a request stays identifiable through the version header above
            if ($headerAgent === '') {
                continue;
            }

            // the name is written back as the integrator spelled it, header names are case
            // insensitive and there is no reason to rewrite theirs
            $headers[$index] = substr($header, 0, strlen('user-agent:'))
                . ' ' . self::appendSdkAgent($headerAgent);
        }

        $options = [
                CURLOPT_USERPWD    => $this->username . ':' . $this->password,
                CURLOPT_HTTPAUTH   => CURLAUTH_ANY,
                CURLOPT_HTTPHEADER => $headers,
            ] + $this->curlOptions;

        $options[CURLOPT_USERAGENT] = self::appendSdkAgent(
            isset($options[CURLOPT_USERAGENT]) && is_string($options[CURLOPT_USERAGENT])
                ? $options[CURLOPT_USERAGENT]
                : ''
        );

        return new CurlRequest($url, $options);
    }

    /**
     * Appends the token of the sdk to a user agent of the integrator, so the api log can attribute
     * the request even when the caller sets an agent of their own. Their agent stays first and
     * intact, since a User-Agent is defined as a list of product tokens.
     *
     * The token is looked for at a product token boundary, at the start or after whitespace, so an
     * agent which merely ends with our name, eg. a fork called my-justimmo-php-sdk, still gets it.
     */
    private static function appendSdkAgent(string $ownAgent): string
    {
        $sdkAgent = 'justimmo-php-sdk/' . self::getSdkVersion() . ' (php ' . PHP_VERSION . ')';
        $ownAgent = trim($ownAgent);

        if ($ownAgent === '') {
            return $sdkAgent;
        }

        if (preg_match('/(?:^|\s)justimmo-php-sdk\//', $ownAgent) === 1) {
            return $ownAgent;
        }

        return $ownAgent . ' ' . $sdkAgent;
    }
}
