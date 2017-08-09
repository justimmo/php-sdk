<?php

namespace Justimmo\Api\Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Justimmo\Api\Entity\RealtyType;
use Justimmo\Api\Exception\ClientException;
use Justimmo\Api\Request\ExposeRequest;
use Justimmo\Api\Request\RealtyTypeRequest;
use Justimmo\Api\Request\TenantRequest;
use Justimmo\Api\ResultSet\Pager;

/**
 * Client error tests
 *
 * Entity requests are tested in Justimmo\Api\Tests\Entity classes
 */
class ClientTest extends TestCase
{
    protected function createRequestException($statusCode, $content = '')
    {
        return new RequestException(
            'Error',
            new Request('GET', '/test'),
            new Response($statusCode, ['Content-Type' => 'application/json'], $content)
        );
    }

    /**
     * @expectedException \Justimmo\Api\Exception\RequestException
     * @expectedExceptionMessage Not Valid
     */
    public function testGuzzleRequestException()
    {
        $client = $this->createClient([
            new RequestException('Not Valid', new Request('GET', '/test')),
        ]);

        $client->request(new TenantRequest());
    }

    /**
     * @expectedException \Justimmo\Api\Exception\AuthorizationException
     */
    public function test401()
    {
        $client = $this->createClient([
            $this->createRequestException(401),
            $this->createRequestException(401),
        ]);

        $client->request(new TenantRequest());
    }

    /**
     * @expectedException \Justimmo\Api\Exception\AuthorizationException
     */
    public function test403()
    {
        $client = $this->createClient([
            $this->createRequestException(403),
        ]);

        $client->request(new TenantRequest());
    }

    /**
     * @expectedException \Justimmo\Api\Exception\ServerException
     */
    public function test500()
    {
        $client = $this->createClient([
            $this->createRequestException(500),
        ]);

        $client->request(new TenantRequest());
    }

    /**
     * @expectedException \Justimmo\Api\Exception\NotFoundException
     */
    public function test404()
    {
        $client = $this->createClient([
            $this->createRequestException(404),
        ]);

        $client->request(new TenantRequest());
    }

    /**
     * @expectedException \Justimmo\Api\Exception\ClientException
     * @expectedExceptionMessage Response content type is not "application/json".
     */
    public function testInvalidFormat()
    {
        $client = $this->createClient([
            $this->createResponse('{}', 200, ['Content-type' => 'text/html']),
        ]);

        $client->request(new TenantRequest());
    }

    public function testValidationErrors()
    {
        $e = null;
        try {
            $client = $this->createClient([
                new RequestException(
                    'Validation Errors',
                    new Request('GET', '/test'),
                    $this->createResponse(json_encode([
                        'message'          => 'Validation Errors',
                        'validationErrors' => [
                            'type'       => 'Required',
                            'realtyType' => 'Invalid',
                        ],
                    ]), 400)
                ),
            ]);

            $client->request(new TenantRequest());
        } catch (ClientException $exception) {
            $e = $exception;
        }

        $this->assertInstanceOf(ClientException::class, $e);
        $this->assertEquals('Validation Errors', $e->getMessage());
        $this->assertEquals(400, $e->getCode());
        $this->assertEquals(2, count($e->getValidationErrors()));
        $this->assertEquals('Required', $e->getValidationErrors()['type']);
        $this->assertEquals('Invalid', $e->getValidationErrors()['realtyType']);
    }

    public function testNormalResponse()
    {
        $client = $this->createClient([
            $this->createResponse('test', 200, ['Content-type' => 'application/pdf'])
        ]);

        $response = $client->request(new ExposeRequest(1234, 1234));

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testJsonResponse()
    {
        $client = $this->createClient([
            $this->createResponse('list.json'),
            $this->createResponse('single.json'),
            $this->createResponse('list_paginated.json'),
        ]);

        $pager = $client->request(new RealtyTypeRequest());
        $this->assertInstanceOf(Pager::class, $pager);
        $this->assertEquals(13, $pager->getNbResults());

        $entity = $pager[0];
        $this->assertInstanceOf(RealtyType::class, $entity);
        $this->assertEquals(5, $entity->getId());
        $this->assertEquals('Büro / Praxis', $entity->getName());

        $entity = $pager[1];
        $this->assertInstanceOf(RealtyType::class, $entity);
        $this->assertEquals(6, $entity->getId());
        $this->assertEquals('Einzelhandel', $entity->getName());

        $entity = $client->request(new RealtyTypeRequest());
        $this->assertInstanceOf(RealtyType::class, $entity);
        $this->assertEquals(5, $entity->getId());
        $this->assertEquals('Büro / Praxis', $entity->getName());

        $pager = $client->request((new RealtyTypeRequest())->paginate(2, 2));
        $this->assertInstanceOf(Pager::class, $pager);
        $this->assertEquals(13, $pager->getNbResults());
        $this->assertEquals(2, count($pager));
        $this->assertEquals(2, $pager->getPage());
        $this->assertEquals(1, $pager->getFirstPage());
        $this->assertEquals(7, $pager->getLastPage());

    }
}
