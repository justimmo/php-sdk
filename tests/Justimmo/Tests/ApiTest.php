<?php
namespace Justimmo\Tests;

use Composer\InstalledVersions;
use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\NullCache;
use Justimmo\Exception\AuthenticationException;
use Justimmo\Exception\InvalidRequestException;
use Justimmo\Exception\JustimmoException;
use Justimmo\Exception\NotFoundException;
use Justimmo\Exception\StatusCodeException;
use Justimmo\Exception\ValidationException;
use Psr\Log\NullLogger;

class ApiTest extends TestCase
{
    /**
     * @var JustimmoApi
     */
    protected $api;

    public function setUp(): void
    {
        $this->api = $this->getMockBuilder('Justimmo\Api\JustimmoApi')
            ->setConstructorArgs(array('username', 'password', new NullLogger(), new NullCache()))
            ->setMethods(array('createRequest'))
            ->getMock();
    }

    public function testWrongUserData()
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Bad Username / Password 401');
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 401));

        $this->api->callRealtyList();
    }

    public function test404Response()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Api call not found: 404');

        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 404));

        $this->api->callRealtyList();
    }

    public function test500Response()
    {
        $this->expectException(StatusCodeException::class);
        $this->expectExceptionMessage('The Api call returned status code 500');

        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 500));

        $this->api->callRealtyList();
    }

    public function testInvalidRequestWithoutBody()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The Api call returned status code 400');

        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 400));

        $this->api->callRealtyList();
    }

    public function testInvalidRequestWithBody()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('zimmer_von ["test" is not a number.]');

        $this->api->method('createRequest')->willReturn(new MockCurlRequest('<justimmo><error>zimmer_von ["test" is not a number.]</error></justimmo>', 400));

        $this->api->callRealtyList();
    }

    public function test422XmlResponse()
    {
        $this->api->method('createRequest')->willReturn(
            new MockCurlRequest($this->getFixtures('v1/validation_error.xml'), 422, 'text/xml; charset=UTF-8')
        );

        try {
            $this->api->callRealtyList();
            $this->fail('no ValidationException has been thrown');
        } catch (ValidationException $e) {
            // a 422 must stay catchable for everybody who catches the general exception
            $this->assertInstanceOf(InvalidRequestException::class, $e);
            $this->assertSame('orderType: The order type should be either "asc" or "desc".', $e->getMessage());
            $this->assertSame([
                [
                    'propertyPath' => 'orderType',
                    'message'      => 'The order type should be either "asc" or "desc".',
                ],
            ], $e->getViolations());
        }
    }

    public function test422JsonResponse()
    {
        $this->api->method('createRequest')->willReturn(
            new MockCurlRequest($this->getFixtures('v1/validation_error_multiple.json'), 422, 'application/json')
        );

        try {
            $this->api->callRealtyIds();
            $this->fail('no ValidationException has been thrown');
        } catch (ValidationException $e) {
            $this->assertSame([
                [
                    'propertyPath' => 'orderType',
                    'message'      => 'The order type should be either "asc" or "desc".',
                ],
                [
                    'propertyPath' => 'filter.zipCodes[0]',
                    'message'      => 'Diese Zeichenkette ist zu kurz. Sie sollte mindestens 4 Zeichen haben.',
                ],
            ], $e->getViolations());
            $this->assertStringContainsString('filter.zipCodes[0]', $e->getMessage());
        }
    }

    /**
     * In xml every violation is a repeated <violations> element rather than a list, so more than
     * one of them has to be read as well. The json path is covered above with two, this is the
     * same body in the format the list and detail endpoints answer with
     */
    public function test422XmlResponseWithSeveralViolations()
    {
        $this->api->method('createRequest')->willReturn(
            new MockCurlRequest($this->getFixtures('v1/validation_error_multiple.xml'), 422, 'text/xml; charset=UTF-8')
        );

        try {
            $this->api->callRealtyList();
            $this->fail('no ValidationException has been thrown');
        } catch (ValidationException $e) {
            $this->assertSame([
                [
                    'propertyPath' => 'orderType',
                    'message'      => 'The order type should be either "asc" or "desc".',
                ],
                [
                    'propertyPath' => 'filter.zipCodes[0]',
                    'message'      => 'Diese Zeichenkette ist zu kurz. Sie sollte mindestens 4 Zeichen haben.',
                ],
            ], $e->getViolations());
            $this->assertStringContainsString('filter.zipCodes[0]', $e->getMessage());
        }
    }

    /**
     * A body which cannot be parsed must not break the exception. That happens with a proxy
     * answering html, a truncated response, or a content type which does not match the body — in
     * all of those the message of the sdk is kept and no violation is invented
     */
    public function testAnUnparsableBodyKeepsTheMessageOfTheSdk()
    {
        $bodies = [
            'broken xml'            => ['<response><unclosed>', 'text/xml; charset=UTF-8'],
            'truncated json'        => ['{"violations": [', 'application/json'],
            'html from a proxy'     => ['<html><body>502 Bad Gateway</body></html>', 'text/html'],
            'json body, xml header' => [$this->getFixtures('v1/validation_error_multiple.json'), 'text/xml; charset=UTF-8'],
            'xml body, json header' => [$this->getFixtures('v1/validation_error.xml'), 'application/json'],
        ];

        foreach ($bodies as $case => list($body, $contentType)) {
            $exception = new ValidationException('The Api call returned status code 422');
            $exception->setResponse($body, $contentType);

            $this->assertSame(
                'The Api call returned status code 422',
                $exception->getMessage(),
                'the message of the sdk has to survive a body of the kind: ' . $case
            );
            $this->assertSame(
                [],
                $exception->getViolations(),
                'no violation may be invented for a body of the kind: ' . $case
            );
        }
    }

    /**
     * createRequest() stays overridable without a return type until 2.x, so a request object of an
     * integrator may not have getContentType(), which is new in this version. The status exception
     * of the sdk has to be thrown anyway, and the format of the error body is recognised by
     * sniffing it instead of by its content type
     */
    public function testARequestWithoutGetContentTypeStillThrowsTheStatusException()
    {
        $legacyRequest = new class ($this->getFixtures('v1/validation_error_multiple.json')) {
            private $content;

            public function __construct($content)
            {
                $this->content = $content;
            }

            public function setOption($key, $value)
            {
                return $this;
            }

            public function get()
            {
                return $this->content;
            }

            public function getError()
            {
                return null;
            }

            public function getStatusCode()
            {
                return 422;
            }

            public function getContent()
            {
                return $this->content;
            }

            // deliberately no getContentType(), that is the point of this test
        };

        $this->api->method('createRequest')->willReturn($legacyRequest);

        try {
            $this->api->callRealtyIds();
            $this->fail('no ValidationException has been thrown');
        } catch (ValidationException $e) {
            // the body was recognised as json without a content type telling us so
            $this->assertCount(2, $e->getViolations());
            $this->assertStringContainsString('filter.zipCodes[0]', $e->getMessage());
        }
    }

    /**
     * A response which stopped execution before has to stop it now as well, and with something
     * the catch blocks of the caller still catch. The third column is the class the sdk threw
     * before the status dispatch was introduced, so a `catch` written against an older version
     * keeps working — for a 422 that is InvalidRequestException, of which ValidationException
     * is a subclass.
     *
     * @dataProvider statusCodeProvider
     */
    public function testEveryStatusCodeThrowsSomethingTheOldCatchBlocksStillCatch($statusCode, $expected, $catchableAs)
    {
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', $statusCode));

        try {
            $this->api->callRealtyList();
        } catch (JustimmoException $e) {
            $this->assertInstanceOf($expected, $e);
            $this->assertInstanceOf($catchableAs, $e);
            $this->assertInstanceOf(\Exception::class, $e);

            return;
        }

        $this->fail('status code ' . $statusCode . ' did not stop the execution');
    }

    public function statusCodeProvider()
    {
        return [
            'moved permanently'     => [301, StatusCodeException::class, StatusCodeException::class],
            'bad request'           => [400, InvalidRequestException::class, InvalidRequestException::class],
            'unauthorized'          => [401, AuthenticationException::class, AuthenticationException::class],
            'forbidden'             => [403, InvalidRequestException::class, InvalidRequestException::class],
            'not found'             => [404, NotFoundException::class, NotFoundException::class],
            'method not allowed'    => [405, InvalidRequestException::class, InvalidRequestException::class],
            'unprocessable entity'  => [422, ValidationException::class, InvalidRequestException::class],
            'too many requests'     => [429, InvalidRequestException::class, InvalidRequestException::class],
            'internal server error' => [500, StatusCodeException::class, StatusCodeException::class],
            'bad gateway'           => [502, StatusCodeException::class, StatusCodeException::class],
            'service unavailable'   => [503, StatusCodeException::class, StatusCodeException::class],
        ];
    }

    /**
     * A 3xx or a 5xx becomes a StatusCodeException, which never reads the body, so the body must
     * not be asked for either. The request of this test fails loudly if it is
     */
    public function testAServerErrorDoesNotReadTheBody()
    {
        $request = new class {
            public function setOption($key, $value)
            {
                return $this;
            }

            public function get()
            {
                return '';
            }

            public function getError()
            {
                return null;
            }

            public function getStatusCode()
            {
                return 500;
            }

            public function getContentType()
            {
                return 'text/xml; charset=UTF-8';
            }

            public function getContent()
            {
                throw new \LogicException('the body must not be read for a server error');
            }
        };

        $this->expectException(StatusCodeException::class);
        $this->expectExceptionMessage('The Api call returned status code 500');

        $this->api->method('createRequest')->willReturn($request);

        $this->api->callRealtyList();
    }

    /**
     * A 404 carries a generic body, its message must not replace the message of the sdk
     */
    public function test404ResponseKeepsItsMessage()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Api call not found: 404');

        $this->api->method('createRequest')->willReturn(
            new MockCurlRequest($this->getFixtures('v1/not_found.xml'), 404, 'text/xml; charset=UTF-8')
        );

        $this->api->callRealtyDetail(0);
    }

    /**
     * Every request must be attributable to the sdk and to its version
     */
    public function testRequestsIdentifyTheSdk()
    {
        $api    = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $method = new \ReflectionMethod(JustimmoApi::class, 'createRequest');

        $request = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list');
        $headers = $request->getOption(CURLOPT_HTTPHEADER);

        $version = InstalledVersions::getPrettyVersion('justimmo/php-sdk');

        $this->assertContains('X-Justimmo-PHP-SDK-Version: ' . $version, $headers);
        $this->assertStringStartsWith('justimmo-php-sdk/', $request->getOption(CURLOPT_USERAGENT));
    }

    /**
     * Headers set by the integrator must survive
     */
    public function testCustomHeadersAreKept()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_HTTPHEADER, ['X-Custom: keep me']);

        $method = new \ReflectionMethod(JustimmoApi::class, 'createRequest');

        $headers = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list')
            ->getOption(CURLOPT_HTTPHEADER);

        $version = InstalledVersions::getPrettyVersion('justimmo/php-sdk');

        $this->assertContains('X-Custom: keep me', $headers);
        $this->assertContains('X-Justimmo-PHP-SDK-Version: ' . $version, $headers);
    }

    /**
     * A user agent of the integrator is kept and the sdk token appended to it, so the request
     * stays attributable in the api log even where the caller sets an agent of their own
     */
    public function testSdkTokenIsAppendedToACustomUserAgent()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_USERAGENT, 'ImmoPlugin/3.2');

        $method  = new \ReflectionMethod(JustimmoApi::class, 'createRequest');
        $request = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list');

        $userAgent = $request->getOption(CURLOPT_USERAGENT);

        $this->assertStringStartsWith('ImmoPlugin/3.2 ', $userAgent);
        $this->assertStringContainsString('justimmo-php-sdk/', $userAgent);
        $this->assertContains(
            'X-Justimmo-PHP-SDK-Version: ' . InstalledVersions::getPrettyVersion('justimmo/php-sdk'),
            $request->getOption(CURLOPT_HTTPHEADER)
        );
    }

    /**
     * A token which merely ends with our name, a fork or a wrapper, is not our token, so the sdk
     * token still has to be appended next to it
     */
    public function testSdkTokenIsAppendedNextToASimilarlyNamedToken()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_USERAGENT, 'my-justimmo-php-sdk/1.0');

        $method  = new \ReflectionMethod(JustimmoApi::class, 'createRequest');
        $request = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list');

        $userAgent = $request->getOption(CURLOPT_USERAGENT);

        $this->assertStringStartsWith('my-justimmo-php-sdk/1.0 ', $userAgent);
        $this->assertSame(2, substr_count($userAgent, 'justimmo-php-sdk/'));
    }

    /**
     * An integrator who carries the sdk token in their own agent does not get it twice
     */
    public function testSdkTokenIsNotAppendedTwice()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_USERAGENT, 'ImmoPlugin/3.2 justimmo-php-sdk/1.3.3');

        $method  = new \ReflectionMethod(JustimmoApi::class, 'createRequest');
        $request = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list');

        $this->assertSame(
            'ImmoPlugin/3.2 justimmo-php-sdk/1.3.3',
            $request->getOption(CURLOPT_USERAGENT)
        );
    }

    /**
     * curl lets a User-Agent in the header list win over CURLOPT_USERAGENT, so an agent set that
     * way has to carry the sdk token too, otherwise the request is not attributable
     */
    public function testSdkTokenIsAppendedToAUserAgentHeader()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_HTTPHEADER, ['X-Custom: keep me', 'User-Agent: ImmoPlugin/3.2']);

        $method  = new \ReflectionMethod(JustimmoApi::class, 'createRequest');
        $headers = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list')
            ->getOption(CURLOPT_HTTPHEADER);

        $this->assertContains('X-Custom: keep me', $headers);

        $userAgent = null;
        foreach ($headers as $header) {
            if (stripos($header, 'user-agent:') === 0) {
                $userAgent = $header;
            }
        }

        $this->assertNotNull($userAgent, 'the user agent header of the integrator must survive');
        $this->assertStringStartsWith('User-Agent: ImmoPlugin/3.2 ', $userAgent);
        $this->assertStringContainsString('justimmo-php-sdk/', $userAgent);
    }

    /**
     * A user agent header which already carries the token is left alone
     */
    public function testSdkTokenIsNotAppendedTwiceToAUserAgentHeader()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_HTTPHEADER, ['User-Agent: ImmoPlugin/3.2 justimmo-php-sdk/1.3.3']);

        $method  = new \ReflectionMethod(JustimmoApi::class, 'createRequest');
        $headers = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list')
            ->getOption(CURLOPT_HTTPHEADER);

        $userAgents = array_values(array_filter($headers, function ($header) {
            return stripos($header, 'user-agent:') === 0;
        }));

        // one agent header, unchanged, and the token in it exactly once
        $this->assertCount(1, $userAgents);
        $this->assertSame('User-Agent: ImmoPlugin/3.2 justimmo-php-sdk/1.3.3', $userAgents[0]);
        $this->assertSame(1, substr_count(implode("\n", $headers), 'justimmo-php-sdk/'));
    }

    /**
     * A user agent header without a value removes the header in curl. That is a deliberate choice
     * of the integrator and is not overruled, the version header still identifies the request
     */
    public function testAnEmptyUserAgentHeaderIsLeftAlone()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->setCurlOption(CURLOPT_HTTPHEADER, ['User-Agent:']);

        $method  = new \ReflectionMethod(JustimmoApi::class, 'createRequest');
        $headers = $method->invoke($api, 'https://api.justimmo.at/rest/v1/objekt/list')
            ->getOption(CURLOPT_HTTPHEADER);

        $this->assertContains('User-Agent:', $headers);
        $this->assertContains(
            'X-Justimmo-PHP-SDK-Version: ' . InstalledVersions::getPrettyVersion('justimmo/php-sdk'),
            $headers
        );
    }

    public function testGenerateUrl()
    {
        $this->assertEquals('https://api.justimmo.at/rest/v1/objekt/list?culture=de&orderby=preis&filter%5Bpreis_von%5D=500&filter%5Bpreis_bis%5D=1500&filter%5Bobjektart_id%5D=5&filter%5Bplz%5D%5B%5D=1020&filter%5Bplz%5D%5B%5D=1030', $this->api->generateUrl('objekt/list', array(
            'culture' => 'de',
            'orderby' => 'preis',
            'filter'  => array(
                'preis_von'    => 500,
                'preis_bis'    => 1500,
                'objektart_id' => 5,
                'plz'          => array('1020', '1030')
            )
        )));

    }
}
