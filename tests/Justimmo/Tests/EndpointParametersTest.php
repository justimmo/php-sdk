<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoNullApi;
use Justimmo\Cache\NullCache;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Psr\Log\NullLogger;

/**
 * An endpoint only accepts the parameters it documents, and ignores the rest. Sending one it does
 * not accept is at best noise and at worst misleading, so the SDK stops sending them
 */
class EndpointParametersTest extends TestCase
{
    /**
     * The SDK sends its culture to the endpoints which are translated, the ones the contract
     * documents culture for, and to no other
     */
    public function testACallSendsTheCultureWhereTheContractDocumentsIt(): void
    {
        $expected = [];
        $sent     = [];
        foreach (JustimmoApi::ENDPOINTS as $method => $endpoint) {
            $reflection = new \ReflectionMethod(JustimmoApi::class, $method);
            $arguments  = array_fill(0, $reflection->getNumberOfRequiredParameters(), 1);

            $url = $this->urlOf(function (JustimmoApi $api) use ($method, $arguments): void {
                // a deprecated call, like callZipCodes(), emits its notice; it is not what this checks
                set_error_handler(fn () => true, E_USER_DEPRECATED);

                try {
                    $api->$method(...$arguments);
                } catch (\Throwable) {
                    // the fake answer may be rejected after the url was built
                } finally {
                    restore_error_handler();
                }
            });

            $expected[$endpoint] = isset(SupportedParameters::PARAMETERS[$method]['culture']);
            $sent[$endpoint]     = str_contains($url, 'culture=de');
        }

        $this->assertSame($expected, $sent);
        $this->assertContains(true, $sent, 'no call sends the culture');
        $this->assertContains(false, $sent, 'every call sends the culture');
    }

    /**
     * A path the SDK does not know is not in the contract either, so it keeps the culture, as
     * every call did before 1.8.0
     */
    public function testACallToAnUnknownPathKeepsTheCulture(): void
    {
        $url = $this->urlOf(function (JustimmoApi $api): void {
            $api->call('objekt/unknown');
        });

        $this->assertStringContainsString('culture=de', $url);
    }

    /**
     * Only the default is withheld. A caller who names the culture is still obeyed
     */
    public function testAnIdsCallSendsACultureItWasGiven(): void
    {
        $url = $this->urlOf(function (JustimmoApi $api): void {
            $api->callRealtyIds(['culture' => 'en']);
        });

        $this->assertStringContainsString('culture=en', $url);
    }

    /**
     * objekt/detail documents neither alleProjektObjekte nor objektIds
     */
    public function testARealtyDetailCallCarriesThePicturesizeAlone(): void
    {
        $api = $this->detailSpy();

        $this->prepare(new RealtyQuery($api, new RealtyWrapper($mapper = new RealtyMapper()), $mapper))
            ->findPk(1);

        $this->assertSame(['picturesize' => ['big']], $api->params);
    }

    /**
     * projekt/detail documents both, so nothing changes for a project
     */
    public function testAProjectDetailCallKeepsAllThree(): void
    {
        $api = $this->detailSpy();

        $this->prepare(new ProjectQuery($api, new ProjectWrapper($mapper = new ProjectMapper()), $mapper))
            ->findPk(1);

        $this->assertSame(
            ['picturesize' => ['big'], 'alleProjektObjekte' => 1, 'objektIds' => 1],
            $api->params
        );
    }

    /**
     * A filter or a sort left on the query is not a detail parameter either
     */
    public function testADetailCallDropsTheListParameters(): void
    {
        $api   = $this->detailSpy();
        $query = new RealtyQuery($api, new RealtyWrapper($mapper = new RealtyMapper()), $mapper);

        $query->setLimit(5)->orderByPrice('desc')->filterByZipCode('1010')->findPk(1);

        $this->assertSame([], $api->params);
    }

    /**
     * A list in English and the detail of one of its realties in German was what a culture on the
     * query gave before
     */
    public function testARealtyDetailCallCarriesTheCultureOfTheQuery(): void
    {
        $api = $this->detailSpy();

        (new RealtyQuery($api, new RealtyWrapper($mapper = new RealtyMapper()), $mapper))
            ->setCulture('en')
            ->findPk(1);

        $this->assertSame(['culture' => 'en'], $api->params);
    }

    public function testAProjectDetailCallCarriesTheCultureOfTheQuery(): void
    {
        $api = $this->detailSpy();

        (new ProjectQuery($api, new ProjectWrapper($mapper = new ProjectMapper()), $mapper))
            ->setCulture('en')
            ->findPk(1);

        $this->assertSame(['culture' => 'en'], $api->params);
    }

    /**
     * The culture reaches a list call the way set('culture', …) always sent it
     */
    public function testAListCallCarriesTheCultureOfTheQuery(): void
    {
        $query = new RealtyQuery(new JustimmoNullApi(), new RealtyWrapper($mapper = new RealtyMapper()), $mapper);

        $this->assertSame(['culture' => 'en'], $query->setCulture('en')->getParams());
    }

    private function prepare(AbstractQuery $query): AbstractQuery
    {
        return $query
            ->setPicturesize('big')
            ->set('alleProjektObjekte', 1)
            ->set('objektIds', 1);
    }

    /**
     * The url the api builds for one call, without a request leaving the machine
     */
    private function urlOf(callable $call): string
    {
        $url = '';
        $api = $this->getMockBuilder(JustimmoApi::class)
            ->setConstructorArgs(['username', 'password', new NullLogger(), new NullCache()])
            ->onlyMethods(['createRequest'])
            ->getMock();

        $api->method('createRequest')->willReturnCallback(function ($requested) use (&$url) {
            $url = $requested;

            return new MockCurlRequest('{}', 200, 'application/json');
        });

        $call($api);

        return $url;
    }

    /**
     * A null api which records what findPk() handed to the detail call
     */
    private function detailSpy(): JustimmoNullApi
    {
        return new class extends JustimmoNullApi {
            /**
             * @var array<string, mixed>
             */
            public array $params = [];

            public function callRealtyDetail($pk, array $params = [])
            {
                $this->params = $params;

                return '<?xml version="1.0" encoding="UTF-8"?><justimmo></justimmo>';
            }

            public function callProjectDetail($pk, array $params = [])
            {
                $this->params = $params;

                return '<?xml version="1.0" encoding="UTF-8"?><justimmo></justimmo>';
            }
        };
    }
}
