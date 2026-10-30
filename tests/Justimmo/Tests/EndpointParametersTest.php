<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoNullApi;
use Justimmo\Cache\NullCache;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
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
     * The ids endpoints answer with a plain array of ids. There is no text in that response for a
     * culture to translate, and none of the three documents the parameter
     *
     * @return array<int, array{0: string}>
     */
    public function idsCallProvider(): array
    {
        return [
            ['callRealtyIds'],
            ['callProjectIds'],
            ['callEmployeeIds'],
        ];
    }

    /**
     * @dataProvider idsCallProvider
     */
    public function testAnIdsCallDoesNotSendTheCulture(string $call): void
    {
        $url = $this->urlOf(function (JustimmoApi $api) use ($call): void {
            $api->$call();
        });

        $this->assertStringNotContainsString('culture', $url, $call);
    }

    public function testEveryOtherCallStillSendsTheCulture(): void
    {
        $this->assertStringContainsString('culture=de', $this->urlOf(function (JustimmoApi $api): void {
            $api->callRealtyList();
        }));

        $this->assertStringContainsString('culture=de', $this->urlOf(function (JustimmoApi $api): void {
            $api->callRealtyDetail(1);
        }));

        $this->assertStringContainsString('culture=de', $this->urlOf(function (JustimmoApi $api): void {
            $api->callEmployeeList();
        }));
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
