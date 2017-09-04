<?php

namespace Justimmo\Api;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Justimmo\Api\Exception\ClientException;
use Justimmo\Api\Exception\RequestException as ApiRequestException;
use Justimmo\Api\Authorization\AccessTokenProvider;
use Justimmo\Api\Hydration\EntityHydrator;
use Justimmo\Api\Request\EntityRequest;
use Justimmo\Api\ResultSet\Pager;
use Justimmo\Api\Request\ApiRequest;
use Justimmo\Api\ResultSet\ResultSet;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const BASE_URL = 'https://api.justimmo.at/rest/v2';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * @var AccessTokenProvider
     */
    protected $accessTokenProvider;

    /**
     * @var EntityHydrator
     */
    protected $hydrator;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client  $guzzle
     * @param AccessTokenProvider $accessTokenProvider
     * @param EntityHydrator      $hydrator
     */
    public function __construct(\GuzzleHttp\Client $guzzle, AccessTokenProvider $accessTokenProvider, EntityHydrator $hydrator)
    {
        $this->guzzle              = $guzzle;
        $this->accessTokenProvider = $accessTokenProvider;
        $this->hydrator            = $hydrator;
    }

    /**
     * Exectues an api request and returns a formatted response
     *
     * @param ApiRequest $request
     *
     * @return Pager|\Justimmo\Api\Entity\Entity|\Justimmo\Api\Entity\Entity[]|ResponseInterface
     */
    public function request(ApiRequest $request)
    {
        if ($request instanceof EntityRequest) {
            $query = $request->getQuery();
            return $this->buildEntities(
                $this->getResponse($request),
                $request->getEntityClass(),
                !empty($query['limit']) ? $query['limit'] : null,
                !empty($query['offset']) ? $query['offset'] : null
            );
        }

        return $this->getResponse($request);
    }

    /**
     * Builds entities from a response
     *
     * @param ResponseInterface $response
     * @param string            $entityClass
     * @param int               $limit
     * @param int               $offset
     *
     * @return array|Entity\Entity|ResultSet
     */
    protected function buildEntities(ResponseInterface $response, $entityClass, $limit = null, $offset = null)
    {
        $return = $this->decodeResponse($response);
        if (count($return) === 0) {
            return [];
        }

        if (count($return) === 2 && array_key_exists('count', $return) && array_key_exists('results', $return)) {
            $entities = [];
            foreach ($return['results'] as $result) {
                $entities[] = $this->hydrator->hydrate($result, $entityClass);
            }

            return Pager::create(
                $entities,
                $return['count'],
                $limit,
                $offset
            );
        }

        return $this->hydrator->hydrate($return, $entityClass);
    }

    /**
     * Decodes response body into an array
     *
     * @param ResponseInterface $response
     *
     * @return array
     * @throws ClientException
     */
    protected function decodeResponse(ResponseInterface $response)
    {
        if ($response->getHeaderLine('Content-type') != 'application/json') {
            throw ClientException::invalidFormat('Response content type is not "application/json".');
        }

        $json = json_decode((string) $response->getBody(), true);
        if ($json === null) {
            throw ClientException::invalidFormat();
        }

        return $json;
    }

    /**
     * Executes an api request and retries once
     *
     * @param string $method
     * @param string $url
     * @param array  $query
     * @param array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function executeRequest($method, $url, $query, array $options = [])
    {
        $triesLeft = 2;
        $token     = $this->accessTokenProvider->getAccessToken();

        $options = array_merge($options, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'query'   => $query,
        ]);

        while ($triesLeft > 0) {
            $triesLeft--;
            try {
                return $this->guzzle->request($method, $url, $options);
            } catch (GuzzleRequestException $e) {
                if ($triesLeft <= 0 || $e->getCode() != 401) {
                    throw $e;
                }
                $options['headers']['Authorization'] = 'Bearer ' . $this->accessTokenProvider->refreshAccessToken();
            }
        }
    }

    /**
     * Returns a response for a request or throws an exception
     *
     * @param ApiRequest $request
     *
     * @return ResponseInterface
     * @throws ApiRequestException
     */
    protected function getResponse(ApiRequest $request)
    {
        $query = array_merge(($this->guzzle->getConfig('query') ?: []), $request->getQuery());

        try {
            return $this->executeRequest($request->getMethod(), $this->buildUrl($request->getPath()), $query, $request->getGuzzleOptions());
        } catch (\Exception $e) {
            throw ApiRequestException::createFromException($e);
        }
    }

    /**
     * Appends the path to the current url
     *
     * @param $path
     *
     * @return string
     */
    protected function buildUrl($path)
    {
        return self::BASE_URL . $path;
    }
}
