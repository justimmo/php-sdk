<?php

namespace Justimmo\Api;

use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Justimmo\Api\Exception\ClientException;
use Justimmo\Api\Exception\RequestException as ApiRequestException;
use Justimmo\Api\Authorization\AccessTokenProvider;
use Justimmo\Api\Hydration\EntityHydrator;
use Justimmo\Api\Pager\ListPager;
use Justimmo\Api\Request\ApiRequest;
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
     * @return ListPager|\Justimmo\Api\Entity\Entity|\Justimmo\Api\Entity\Entity[]
     */
    public function request(ApiRequest $request)
    {
        $query = array_merge(($this->guzzle->getConfig('query') ?: []), $request->getQuery());

        try {
            $response = $this->executeRequest($request->getMethod(), $this->buildUrl($request->getPath()), $query);
        } catch (\Exception $e) {
            throw ApiRequestException::createFromException($e);
        }

        $return = $this->decodeResponse($response);
        if (count($return) === 0) {
            return [];
        }

        if (count($return) === 2 && array_key_exists('count', $return) && array_key_exists('results', $return)) {
            $entities = [];
            foreach ($return['results'] as $result) {
                $entities[] = $this->hydrator->hydrate($result, $request->getEntityClass());
            }

            return ListPager::create(
                $entities,
                $return['count'],
                !empty($query['limit']) ? $query['limit'] : null,
                !empty($query['offset']) ? $query['offset'] : null
            );
        }

        return $this->hydrator->hydrate($return, $request->getEntityClass());
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
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function executeRequest($method, $url, $query)
    {
        $triesLeft = 2;
        $token     = $this->accessTokenProvider->getAccessToken();

        while ($triesLeft > 0) {
            $triesLeft--;
            try {
                return $this->guzzle->request($method, $url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'query'   => $query,
                ]);
            } catch (GuzzleRequestException $e) {
                if ($triesLeft <= 0 || $e->getCode() != 401) {
                    throw $e;
                }
                $token = $this->accessTokenProvider->refreshAccessToken();
            }
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
