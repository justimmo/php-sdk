<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Api\JustimmoNullApi;
use Justimmo\Cache\NullCache;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\BasicDataMapper;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyInquiryMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Request\RealtyInquiryRequest;
use Psr\Log\NullLogger;

/**
 * The SDK and the api's contract, data/sdk-supported-parameters.json, describe the same endpoints
 *
 * The json says which endpoints the api has and what each accepts. JustimmoApi::ENDPOINTS says which
 * SDK method sends which endpoint. These tests compare the two whenever the json is refreshed, and
 * each failure says what to change. CONTRIBUTING.md has the steps.
 */
class ContractTest extends TestCase
{
    use DocumentedTypes;

    /**
     * Public methods which set no parameter of their own, keyed by class
     */
    const NOT_A_PARAMETER = [
        RealtyQuery::class          => ['__call', '__construct', 'clear', 'filter', 'getDetailCall', 'getIdsCall', 'getListCall', 'getParams', 'isStrict', 'order', 'orderBy', 'set', 'strict'],
        ProjectQuery::class         => ['__call', '__construct', 'clear', 'filter', 'getDetailCall', 'getIdsCall', 'getListCall', 'getParams', 'isStrict', 'order', 'orderBy', 'set', 'strict'],
        EmployeeQuery::class        => ['__call', '__construct', 'clear', 'filter', 'getDetailCall', 'getIdsCall', 'getListCall', 'getParams', 'isStrict', 'order', 'orderBy', 'set', 'strict'],
        BasicDataQuery::class       => ['__construct', 'clear', 'getParams', 'set'],
        RealtyInquiryRequest::class => ['__construct', 'getCategory', 'getCity', 'getCompany', 'getCountry', 'getEmail', 'getFirstName', 'getIsRealtyOwner', 'getLastName', 'getMessage', 'getPhone', 'getRealtyId', 'getRealtyOwnerConsultationRequest', 'getSalutationId', 'getStreet', 'getSuffix', 'getTitle', 'getZipCode', 'isFundingInquiry'],
    ];

    /**
     * The query classes whose find…() methods call the api
     */
    const QUERIES = [RealtyQuery::class, ProjectQuery::class, EmployeeQuery::class, BasicDataQuery::class];

    /**
     * Every class whose methods set the parameters of a call: the queries, and the inquiry, which
     * send() posts
     */
    const SENDERS = [...self::QUERIES, RealtyInquiryRequest::class];

    protected function setUp(): void
    {
        self::forgetTheDeprecationNotices();
    }

    public function testEveryEndpointHasAnSdkMethod(): void
    {
        $document = self::contract();

        $missing = array_values(array_diff(
            array_keys($document['endpoints']),
            JustimmoApi::ENDPOINTS,
            array_keys($document['undocumented_endpoints'] ?? [])
        ));

        $this->assertSame([], $missing, self::explain(
            'The api documents these endpoints, but no SDK method sends them',
            $missing,
            'Add a method to JustimmoApi, JustimmoNullApi and JustimmoApiInterface, put it into JustimmoApi::ENDPOINTS, and give the query a method that calls it. If the SDK must not offer the endpoint, ask the api team to list it in undocumented_endpoints.',
            'The api adds an endpoint'
        ));
    }

    public function testEverySdkMethodSendsADocumentedEndpoint(): void
    {
        $document     = self::contract();
        $undocumented = $document['undocumented_endpoints'] ?? [];

        $wrong = [];
        foreach (JustimmoApi::ENDPOINTS as $method => $endpoint) {
            if (isset($undocumented[$endpoint])) {
                $wrong[] = $method . '() sends ' . $endpoint . ', which the api lists as undocumented. Send ' . $undocumented[$endpoint]['alias_of'] . ' instead';
            } elseif (!isset($document['endpoints'][$endpoint])) {
                $wrong[] = $method . '() sends ' . $endpoint . ', which the api no longer documents';
            }
        }

        $this->assertSame([], $wrong, self::explain(
            'These SDK methods send an endpoint the api does not document',
            $wrong,
            'Do not remove the method: the api keeps a dropped endpoint in the json, marked deprecated, until the next major. Ask the api team to put it back. If the endpoint was renamed, change its path in JustimmoApi::ENDPOINTS.',
            'The api drops an endpoint'
        ));
    }

    /**
     * Every method which sends a request is in ENDPOINTS, and ENDPOINTS names no other method
     */
    public function testEndpointsListsEveryMethodWhichSendsARequest(): void
    {
        $methods = [];
        $interface = new \ReflectionClass(JustimmoApiInterface::class);
        foreach ($interface->getMethods() as $method) {
            if (preg_match('/^(call|post)[A-Z]/', $method->getName()) === 1) {
                $methods[] = $method->getName();
            }
        }

        $listed = array_keys(JustimmoApi::ENDPOINTS);

        $unlisted = array_values(array_diff($methods, $listed));
        $this->assertSame([], $unlisted, self::explain(
            'These methods of JustimmoApiInterface are missing from JustimmoApi::ENDPOINTS',
            $unlisted,
            'Add each with the endpoint it sends, written as the json writes it, like /objekt/list.',
            'The api adds an endpoint'
        ));

        $unknown = array_values(array_diff($listed, $methods));
        $this->assertSame([], $unknown, self::explain(
            'JustimmoApi::ENDPOINTS names methods which JustimmoApiInterface does not have',
            $unknown,
            'Add the method to JustimmoApiInterface, or remove its entry from JustimmoApi::ENDPOINTS.',
            'The api adds an endpoint'
        ));
    }

    /**
     * Each method sends the path ENDPOINTS gives it, so the generator keys the json's data by the
     * right method
     */
    public function testEveryMethodSendsTheEndpointItIsListedWith(): void
    {
        $wrong = [];
        foreach (JustimmoApi::ENDPOINTS as $method => $endpoint) {
            $sent = $this->pathSentBy($method);

            if ($sent !== $endpoint) {
                $wrong[] = $method . '() sends ' . $sent . ', JustimmoApi::ENDPOINTS says ' . $endpoint;
            }
        }

        $this->assertSame([], $wrong, self::explain(
            'These methods send another endpoint than JustimmoApi::ENDPOINTS says',
            $wrong,
            'A method sends its request with $this->callEndpoint(__FUNCTION__, …), which takes the path from ENDPOINTS. Use it, and correct the path in ENDPOINTS.',
            'The api adds an endpoint'
        ));
    }

    /**
     * An endpoint the api deprecates is deprecated in the SDK too: its method and the query method
     * which calls it carry @deprecated and emit a notice, so a site learns it before the next major
     * removes them
     */
    public function testADeprecatedEndpointIsDeprecatedInTheSdk(): void
    {
        $finders = [];
        foreach (self::QUERIES as $class) {
            $finders[$class] = $this->finders($class);
        }

        $missing = [];
        foreach (SupportedParameters::DEPRECATED_CALLS as $method) {
            foreach ([JustimmoApiInterface::class, JustimmoApi::class] as $class) {
                if (!self::isTaggedDeprecated($class, $method)) {
                    $reflection = new \ReflectionClass($class);
                    $missing[]  = $reflection->getShortName() . '::' . $method . '() has no @deprecated';
                }
            }

            $api       = $this->api();
            $arguments = self::arguments($method);
            $notices   = $this->notices(fn () => self::callWithoutTheAnswer(fn () => $api->$method(...$arguments)));
            if ($notices === []) {
                $missing[] = 'JustimmoApi::' . $method . '() emits no notice';
            }

            foreach ($finders as $class => $calls) {
                $reflection = new \ReflectionClass($class);
                $shortName  = $reflection->getShortName();

                foreach ($calls as $finder => $called) {
                    if (!in_array($method, $called, true)) {
                        continue;
                    }

                    if (!self::isTaggedDeprecated($class, $finder)) {
                        $missing[] = $shortName . '::' . $finder . '() has no @deprecated';
                    }

                    $query     = self::instance($class, new JustimmoNullApi());
                    $arguments = self::finderArguments($class, $finder);
                    $notices   = $this->notices(fn () => self::callWithoutTheAnswer(fn () => $query->$finder(...$arguments)));
                    if (!self::namesTheMethod($notices, $shortName, $finder)) {
                        $missing[] = $shortName . '::' . $finder . '() emits no notice';
                    }
                }
            }
        }

        $this->assertSame([], $missing, self::explain(
            'The api deprecates these endpoints, but the SDK does not warn',
            $missing,
            'Tag the methods @deprecated with the replacement, and call $this->triggerDeprecation() in each with the version of the release, so the notice names it.',
            'The api deprecates an endpoint'
        ));
    }

    /**
     * @return array<string, array{0: class-string}>
     */
    public function senders(): array
    {
        $senders = [];
        foreach (self::SENDERS as $class) {
            $reflection = new \ReflectionClass($class);
            $senders[$reflection->getShortName()] = [$class];
        }

        return $senders;
    }

    /**
     * Every parameter of the endpoints a class calls has a method on the class, and every method
     * sends a parameter one of them accepts
     *
     * @dataProvider senders
     *
     * @param class-string $class
     */
    public function testEveryParameterHasAMethod(string $class): void
    {
        $reflection = new \ReflectionClass($class);
        $shortName  = $reflection->getShortName();

        $methods = [];
        foreach ($this->finders($class) as $called) {
            $methods = [...$methods, ...$called];
        }
        $methods = array_values(array_unique($methods));

        $documented = [];
        foreach ($methods as $method) {
            $documented = [...$documented, ...array_keys(SupportedParameters::PARAMETERS[$method] ?? [])];
        }
        $documented = array_values(array_unique($documented));

        $sent = $this->parametersSent($class);

        // what the api sends by itself counts too, like the culture of JustimmoApi::setCulture()
        $onTheWire = [];
        foreach ($methods as $method) {
            $onTheWire = [...$onTheWire, ...$this->parametersOnTheWire($method)];
        }

        $missing = array_values(array_diff($documented, array_keys($sent), $onTheWire));
        $this->assertSame([], $missing, self::explain(
            'The api documents these parameters of ' . implode(', ', $methods) . ', but ' . $shortName . ' has no method for them',
            $missing,
            'Add a method to ' . $shortName . ' which sets the parameter.',
            'The api adds a filter or a parameter'
        ));

        $unknown = [];
        foreach ($sent as $parameter => $senders) {
            if (in_array($parameter, $documented, true)) {
                continue;
            }

            foreach ($senders as $method) {
                // a method on its way out may still send it: tagged @deprecated, with its notice
                if (self::isTaggedDeprecated($class, $method) && $this->warns($class, $method)) {
                    continue;
                }

                $unknown[] = $method . '() sends ' . $parameter;
            }
        }
        $this->assertSame([], $unknown, self::explain(
            'These ' . $shortName . ' methods send a parameter which none of ' . implode(', ', $methods) . ' accepts',
            $unknown,
            'If the api dropped the parameter, deprecate the method in a 1.x release and remove it in the next major. A deprecated method needs its @deprecated tag and its notice.',
            'The api drops a filter or a parameter'
        ));
    }

    /**
     * Every parameter an endpoint documents reaches it: set with its method, then sent by the
     * finder which calls the endpoint, through JustimmoApi up to the url
     *
     * @dataProvider senders
     *
     * @param class-string $class
     */
    public function testEveryFinderSendsTheDocumentedParameters(string $class): void
    {
        $reflection = new \ReflectionClass($class);
        $shortName  = $reflection->getShortName();
        $lost       = $this->lostParameters($class);

        $this->assertSame([], $lost, self::explain(
            'These ' . $shortName . ' finders do not send a parameter the endpoint documents, although a method sets it',
            $lost,
            'Carry the parameter into the call, in the finder and in its JustimmoApi method. findPk() and findIds() carry what the json documents for their endpoint.',
            'The api adds a filter or a parameter'
        ));
    }

    /**
     * The check above, run against a JustimmoApi whose callEmployeeDetail() drops its parameters,
     * as it did before 1.8.0, finds the picture size which findPk() then loses
     */
    public function testTheFinderCheckFindsALostParameter(): void
    {
        $this->assertSame(
            ['findPk() does not send picturesize to callEmployeeDetail'],
            $this->lostParameters(EmployeeQuery::class, ['callEmployeeDetail'])
        );
    }

    /**
     * A finder which drops the culture still sends one: the api client adds its own. The check
     * sees that the value set did not arrive
     */
    public function testTheFinderCheckFindsALostCulture(): void
    {
        $this->assertContains(
            'findPk() does not send culture to callProjectDetail',
            $this->lostParameters(ProjectQuery::class, ['callProjectDetail'])
        );
    }

    /**
     * An endpoint which no query or request calls, like /objekt/expose, is sent by its JustimmoApi
     * method alone, so the method's arguments carry every parameter of it
     */
    public function testAnEndpointWithoutAQuerySendsEveryParameter(): void
    {
        $reached = [];
        foreach (self::SENDERS as $class) {
            foreach ($this->finders($class) as $calls) {
                $reached = [...$reached, ...$calls];
            }
        }

        $missing = [];
        foreach (array_keys(JustimmoApi::ENDPOINTS) as $method) {
            if (in_array($method, $reached, true)) {
                continue;
            }

            $documented = array_keys(SupportedParameters::PARAMETERS[$method] ?? []);
            foreach (array_diff($documented, $this->parametersOnTheWire($method)) as $parameter) {
                $missing[] = 'JustimmoApi::' . $method . '() does not send ' . $parameter;
            }
        }

        $this->assertSame([], $missing, self::explain(
            'No query calls these endpoints, and their JustimmoApi method does not send every parameter',
            $missing,
            'Give the JustimmoApi method an argument for the parameter, or give a query a method which sets it and calls the endpoint.',
            'The api adds a filter or a parameter'
        ));
    }

    /**
     * A parameter whose form the api deprecates has a deprecated method, which warns when that form
     * is used and stays quiet for the others
     */
    public function testADeprecatedParameterHasADeprecatedMethod(): void
    {
        $problems = $this->deprecatedParameterProblems(SupportedParameters::PARAMETERS);

        $this->assertSame([], $problems, self::explain(
            'The api deprecates these parameters, but their methods do not follow',
            $problems,
            'Tag the method @deprecated with the replacement, and call $this->triggerDeprecation() with the version of the release. If only the list form is deprecated, warn only when a list is given.',
            'The api deprecates a parameter'
        ));
    }

    /**
     * The json has no deprecated parameter yet, so the check runs against made-up ones: gruppe of
     * the realty features, and preciseAreaSearch of the realty list
     */
    public function testTheDeprecatedParameterCheckFindsAMethodWhichDoesNotWarn(): void
    {
        $parameters = SupportedParameters::PARAMETERS;
        $parameters['callRealtyFeatures']['gruppe']['deprecated']         = ['plain'];
        $parameters['callRealtyList']['preciseAreaSearch']['deprecated'] = ['plain'];

        $this->assertSame([
            'BasicDataQuery::filterByFeatureGroupId() emits no notice for the plain form',
            'BasicDataQuery::filterByFeatureGroupId() has no @deprecated',
            'RealtyQuery::preciseAreaSearch() emits no notice for the plain form',
            'RealtyQuery::preciseAreaSearch() has no @deprecated',
        ], $this->deprecatedParameterProblems($parameters));
    }

    /**
     * Every method which sets a parameter matches the parameter in the json: the @param of the
     * argument which carries it offers each form the json allows, and for each type the @param tags
     * name, the method sends a value of the json's type
     *
     * @dataProvider senders
     *
     * @param class-string $class
     */
    public function testEveryParameterMethodMatchesTheTypeOfItsParameter(string $class): void
    {
        $reflection = new \ReflectionClass($class);
        $problems   = $this->parameterTypeProblems($class, SupportedParameters::PARAMETERS);

        $this->assertSame([], $problems, self::explain(
            'These ' . $reflection->getShortName() . ' methods do not match the type of their parameter in the json',
            $problems,
            'Make the @param say every form the json allows. Where a method sends a type the json does not have, change the @param, or convert the value before it is sent.',
            'The api changes the type of a filter or a parameter'
        ));
    }

    /**
     * The check above, run against a json in which preciseAreaSearch takes a string and limit a
     * list, finds preciseAreaSearch(), which sends an integer, and setLimit(), which offers no list
     */
    public function testTheParameterTypeCheckFindsAMethodWhichDoesNotMatch(): void
    {
        $parameters = SupportedParameters::PARAMETERS;
        $parameters['callRealtyList']['preciseAreaSearch']['type'] = 'string';
        $parameters['callRealtyList']['limit']['list']             = true;

        $problems = $this->parameterTypeProblems(RealtyQuery::class, $parameters);

        $this->assertContains('RealtyQuery::preciseAreaSearch() with true sends preciseAreaSearch = 1, callRealtyList says string', $problems);
        $this->assertContains('RealtyQuery::setLimit() does not offer the list form of limit of callRealtyList in its @param', $problems);
    }

    /**
     * The decoded contract
     *
     * @return array<string, mixed>
     */
    private static function contract(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../../../data/sdk-supported-parameters.json'), true);
    }

    /**
     * The path of the url the method builds, without a request leaving the machine
     */
    private function pathSentBy(string $method): string
    {
        $path = (string) parse_url($this->urlSentBy($method), PHP_URL_PATH);

        return (string) preg_replace('#^/rest/v1#', '', $path);
    }

    /**
     * The documented parameters which a method of the class sets and a finder does not send to the
     * endpoint it calls, as "findPk() does not send picturesize to callEmployeeDetail"
     *
     * @param class-string       $class
     * @param array<int, string> $dropping see wireApi()
     *
     * @return array<int, string>
     */
    private function lostParameters(string $class, array $dropping = []): array
    {
        $sent = $this->parametersSent($class);

        $lost = [];
        foreach ($this->finders($class) as $finder => $calls) {
            foreach ($calls as $call) {
                $byItself = $this->valuesOnTheWire($call);

                foreach (array_keys(SupportedParameters::PARAMETERS[$call] ?? []) as $parameter) {
                    // a parameter without a method fails testEveryParameterHasAMethod
                    if (!isset($sent[$parameter])) {
                        continue;
                    }

                    // one the api client sends by itself, like the culture, must arrive with the
                    // value the method set, not with the client's own
                    if (!$this->reaches($class, $sent[$parameter], $finder, $parameter, $dropping, $byItself[$parameter] ?? null)) {
                        $lost[] = $finder . '() does not send ' . $parameter . ' to ' . $call;
                    }
                }
            }
        }

        return $lost;
    }

    /**
     * Whether the parameter is in the url of a request the finder sends, after one of the methods
     * set it. Each method is tried on a new instance, with the values valueFor() gives
     *
     * @param class-string       $class
     * @param array<int, string> $methods  the methods which set the parameter
     * @param array<int, string> $dropping see wireApi()
     * @param string|null        $byItself the value the api client sends for the parameter by
     *                                     itself. Then the url must carry the value the method set
     */
    private function reaches(string $class, array $methods, string $finder, string $parameter, array $dropping = [], ?string $byItself = null): bool
    {
        foreach ($methods as $method) {
            $urls     = [];
            $api      = $this->wireApi($urls, $dropping);
            $instance = self::instance($class, $api);
            $setter   = new \ReflectionMethod($class, $method);
            $values   = array_map([self::class, 'valueFor'], $setter->getParameters());
            $finding  = self::finderArguments($class, $finder);

            // the notices, like the one for a picture size the api does not know, are not what is
            // checked here. A request the method sends itself, like paginate() does, is not the finder's
            $set = null;
            $this->notices(function () use ($instance, $method, $values, $finder, $finding, $parameter, &$urls, &$set) {
                self::callWithoutTheAnswer(fn () => $instance->$method(...$values));
                $set  = method_exists($instance, 'getParams') ? ($instance->getParams()[$parameter] ?? null) : null;
                $urls = [];
                self::callWithoutTheAnswer(fn () => $instance->$finder(...$finding));
            });

            foreach ($urls as $url) {
                parse_str((string) parse_url($url, PHP_URL_QUERY), $sent);
                if (array_key_exists($parameter, $sent) && self::carriesTheValueSet($sent[$parameter], $set, $byItself)) {
                    self::forgetTheDeprecationNotices();

                    return true;
                }
            }
        }

        self::forgetTheDeprecationNotices();

        return false;
    }

    /**
     * Whether the value on the wire is the one the method set. Only checked for a parameter the api
     * client sends by itself, like the culture: its own value must not pass for the one set
     */
    private static function carriesTheValueSet(mixed $onTheWire, mixed $set, ?string $byItself): bool
    {
        if ($byItself === null) {
            return true;
        }

        if (is_scalar($set)) {
            return $onTheWire === (string) $set;
        }

        return $onTheWire !== $byItself;
    }

    /**
     * The parameters the method puts on the wire by itself, like the culture, without sdk
     *
     * @return array<int, string>
     */
    private function parametersOnTheWire(string $method): array
    {
        return array_keys($this->valuesOnTheWire($method));
    }

    /**
     * The parameters the method puts on the wire by itself, with their values, without sdk
     *
     * @return array<string, mixed>
     */
    private function valuesOnTheWire(string $method): array
    {
        $query = (string) parse_url($this->urlSentBy($method), PHP_URL_QUERY);
        parse_str($query, $parameters);
        unset($parameters['sdk']);

        return $parameters;
    }

    /**
     * The url the method builds, without a request leaving the machine
     */
    private function urlSentBy(string $method): string
    {
        $urls = [];
        $api  = $this->wireApi($urls);

        // the fake request answers null, which a method with a return type rejects after the url was built
        self::callWithoutTheAnswer(fn () => $api->$method(...self::arguments($method)));

        return $urls[array_key_last($urls)] ?? '';
    }

    /**
     * A JustimmoApi which builds its urls as a site's does and adds each to $urls, without a
     * request leaving the machine
     *
     * @param array<int, string> $urls
     * @param array<int, string> $dropping detail calls, like callEmployeeDetail, which send their
     *                                     id only and drop the parameters they are given. Only the
     *                                     test of the check itself passes them
     */
    private function wireApi(array &$urls, array $dropping = []): JustimmoApi
    {
        $api = $this->getMockBuilder(JustimmoApi::class)
            ->setConstructorArgs(['username', 'password', new NullLogger(), new NullCache()])
            ->onlyMethods(['createRequest', ...$dropping])
            ->getMock();

        $api->method('createRequest')->willReturnCallback(function ($requested) use (&$urls) {
            $urls[] = $requested;

            return new MockCurlRequest('{}', 200, 'application/json');
        });

        foreach ($dropping as $method) {
            $original = new \ReflectionMethod(JustimmoApi::class, $method);
            $api->method($method)->willReturnCallback(fn ($pk) => $original->invoke($api, $pk));
        }

        return $api;
    }

    private function api(): JustimmoApi
    {
        $api = $this->getMockBuilder(JustimmoApi::class)
            ->setConstructorArgs(['username', 'password', new NullLogger(), new NullCache()])
            ->onlyMethods(['createRequest'])
            ->getMock();

        $api->method('createRequest')->willReturn(new MockCurlRequest('{}', 200, 'application/json'));

        return $api;
    }

    /**
     * A value for each required argument of the method: the id of a realty, project or employee
     *
     * @return array<int, mixed>
     */
    private static function arguments(string $method): array
    {
        $reflection = new \ReflectionMethod(JustimmoApi::class, $method);
        $required   = $reflection->getNumberOfRequiredParameters();

        return array_fill(0, $required, 1);
    }

    /**
     * Which JustimmoApi methods each method of the class which calls the api calls, keyed by that
     * method: the find…() methods of a query, send() of a request
     *
     * @param class-string $class
     *
     * @return array<string, array<int, string>>
     */
    private function finders(string $class): array
    {
        $finders = [];
        $reflection = new \ReflectionClass($class);
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (!self::callsTheApi($method->getName())) {
                continue;
            }

            $called    = [];
            $api       = $this->recordingApi($called);
            $query     = self::instance($class, $api);
            $name      = $method->getName();
            $arguments = self::finderArguments($class, $name);

            // a deprecated finder emits its notice here. It is caught, and forgotten below, so a
            // check after this one still sees it
            $this->notices(fn () => self::callWithoutTheAnswer(fn () => $query->$name(...$arguments)));

            if ($called !== []) {
                $finders[$name] = array_values(array_unique($called));
            }
        }

        self::forgetTheDeprecationNotices();

        return $finders;
    }

    /**
     * A value for each required argument of the finder, like the id findPk() takes
     *
     * @param class-string $class
     *
     * @return array<int, mixed>
     */
    private static function finderArguments(string $class, string $finder): array
    {
        $reflection = new \ReflectionMethod($class, $finder);
        $required   = $reflection->getNumberOfRequiredParameters();

        return array_fill(0, $required, 1);
    }

    /**
     * An api which answers as the null api. It adds the name of each method called to $called, and
     * the parameters the method received to $received
     *
     * @param array<int, string>               $called
     * @param array<int, array<string, mixed>> $received
     */
    private function recordingApi(array &$called, array &$received = []): JustimmoApiInterface
    {
        $null = new JustimmoNullApi();
        $api  = $this->createMock(JustimmoApiInterface::class);

        $interface = new \ReflectionClass(JustimmoApiInterface::class);
        foreach ($interface->getMethods() as $method) {
            $name = $method->getName();

            $api->method($name)->willReturnCallback(function (...$arguments) use ($name, $null, &$called, &$received) {
                $called[] = $name;

                // the parameters are the array argument; a detail call takes the id first
                $lists      = array_values(array_filter($arguments, 'is_array'));
                $received[] = $lists[0] ?? [];

                return $null->$name(...$arguments);
            });
        }

        return $api;
    }

    /**
     * Whether the method of a query or a request sends a request to the api
     */
    private static function callsTheApi(string $method): bool
    {
        return str_starts_with($method, 'find') || $method === 'send';
    }

    /**
     * The parameter each public method of the class sets, keyed by the parameter
     *
     * Each method is called on a new instance, with the values valueFor() gives. A parameter counts
     * as set by the method when the method changed it: the inquiry sends every parameter, with an
     * empty value too. Several methods can set one parameter, like setLimit() and paginate().
     *
     * @param class-string $class
     *
     * @return array<string, array<int, string>>
     */
    private function parametersSent(string $class): array
    {
        $sent   = [];
        $before = $this->parametersOf($class, null, []);

        $reflection = new \ReflectionClass($class);
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();
            if (self::callsTheApi($name) || in_array($name, self::NOT_A_PARAMETER[$class], true)) {
                continue;
            }

            // the filters of a list query go into filter[…], and QueryMethodsTest checks them
            if (is_subclass_of($class, AbstractQuery::class) && str_starts_with($name, 'filterBy')) {
                continue;
            }

            $after = $this->parametersOf($class, $name, array_map([self::class, 'valueFor'], $method->getParameters()));

            foreach ($after as $parameter => $value) {
                if (!array_key_exists($parameter, $before) || $before[$parameter] !== $value) {
                    $sent[$parameter][] = $name;
                }
            }
        }

        self::forgetTheDeprecationNotices();

        return $sent;
    }

    /**
     * The parameters a new instance of the class sends after the method was called with the
     * arguments, without the filters. A method which sent a request, like paginate(), is read from
     * what reached the api, since find() empties the query afterwards. Any other method of a query
     * is read with getParams(), the inquiry from what send() posts
     *
     * @param class-string       $class
     * @param array<int, mixed>  $arguments
     *
     * @return array<string, mixed>
     */
    private function parametersOf(string $class, ?string $method, array $arguments): array
    {
        $called   = [];
        $received = [];
        $api      = $this->recordingApi($called, $received);
        $instance = self::instance($class, $api);

        // a notice the method emits, like the one for a picture size the api does not know, is not
        // what is checked here
        $this->notices(function () use ($instance, $method, $arguments) {
            if ($method !== null) {
                self::callWithoutTheAnswer(fn () => $instance->$method(...$arguments));
            }
        });

        if ($instance instanceof RealtyInquiryRequest) {
            $instance->send();

            return $received[array_key_last($received)] ?? [];
        }

        $parameters = $received === [] ? $instance->getParams() : $received[array_key_last($received)];
        unset($parameters['filter']);

        return $parameters;
    }

    /**
     * What the methods of the class which set a parameter do not match in the json, sorted. Each
     * argument is called with a value of each type its @param names, the others with valueFor().
     * The forms are read from the argument which carries the parameter: the only one, or the one
     * of the parameter's name, like $newsletter of registerForNewsletter(). paginate() has none,
     * its page is not a parameter
     *
     * @param class-string                                       $class
     * @param array<string, array<string, array<string, mixed>>> $contract the parameters of each call, as in SupportedParameters::PARAMETERS
     *
     * @return array<int, string>
     */
    private function parameterTypeProblems(string $class, array $contract): array
    {
        $reflection = new \ReflectionClass($class);
        $shortName  = $reflection->getShortName();

        $calls = [];
        foreach ($this->finders($class) as $called) {
            $calls = [...$calls, ...$called];
        }
        $calls = array_values(array_unique($calls));

        $problems = [];
        foreach ($this->parametersSent($class) as $parameter => $methods) {
            foreach (array_unique($methods) as $method) {
                $name      = $shortName . '::' . $method . '()';
                $arguments = (new \ReflectionMethod($class, $method))->getParameters();
                $carrier   = self::carrierOf($arguments, $parameter);

                foreach ($calls as $call) {
                    $entry = $contract[$call][$parameter] ?? null;
                    if ($entry === null) {
                        continue;
                    }

                    if ($carrier !== null) {
                        $offered = self::offeredForms(self::declaredType($carrier));
                        foreach (self::documentedForms($entry) as $form) {
                            if (!isset($offered[$form])) {
                                $problems[] = $name . ' does not offer the ' . $form . ' form of ' . $parameter . ' of ' . $call . ' in its @param';
                            }
                        }
                    }

                    // a method without an argument, like showDetails(), sends a value of its own
                    $tries = $arguments === [] ? [[null, []]] : [];
                    foreach ($arguments as $position => $argument) {
                        foreach (self::offeredForms(self::declaredType($argument)) as $form => $types) {
                            foreach ($types as $type) {
                                $sample = self::sampleOf($type, $argument === $carrier ? $entry : []);
                                if ($sample === null) {
                                    $problems[] = $name . ' names ' . self::unclearType($form, $type) . ' in the @param of $' . $argument->getName() . ', which does not say what it sends';
                                    continue;
                                }

                                $values            = array_map([self::class, 'valueFor'], $arguments);
                                $values[$position] = self::valueOfForm($form, $sample);
                                $tries[]           = [$values[$position], $values];
                            }
                        }
                    }

                    foreach ($tries as [$value, $values]) {
                        $with = $arguments === [] ? '' : ' with ' . self::describe($value);
                        $sent = $this->parametersOf($class, $method, $values);
                        if (!array_key_exists($parameter, $sent) || $sent[$parameter] === null) {
                            $problems[] = $name . $with . ' sends no ' . $parameter;
                            continue;
                        }

                        $problem = self::sentProblem($sent[$parameter], $entry, false);
                        if ($problem !== null) {
                            $problems[] = $name . $with . ' sends ' . $parameter . ' = ' . self::describe($sent[$parameter]) . ', ' . str_replace('the json says', $call . ' says', str_replace('the json allows', $call . ' allows', $problem));
                        }
                    }
                }
            }
        }

        self::forgetTheDeprecationNotices();

        $problems = array_values(array_unique($problems));
        sort($problems, SORT_STRING);

        return $problems;
    }

    /**
     * The argument which carries the parameter: the only one, or the one of the parameter's name,
     * compared without case and underscores
     *
     * @param array<int, \ReflectionParameter> $arguments
     */
    private static function carrierOf(array $arguments, string $parameter): ?\ReflectionParameter
    {
        if (count($arguments) === 1) {
            return $arguments[0];
        }

        $wanted = strtolower(str_replace('_', '', $parameter));
        foreach ($arguments as $argument) {
            if (strtolower($argument->getName()) === $wanted) {
                return $argument;
            }
        }

        return null;
    }

    /**
     * The value the test passes for one argument: its default when it has one, else a value of the
     * type it declares, natively or in its @param tag
     */
    private static function valueFor(\ReflectionParameter $parameter): mixed
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        $type = self::documentedType($parameter);

        return match ($type) {
            'int'            => 1,
            'float'          => 1.5,
            'bool'           => true,
            // not de, the culture the api client sends by itself, so the finder check sees the value set
            'string'         => 'en',
            'array', 'int[]' => [1],
            default          => throw new \LogicException(
                $parameter->getDeclaringClass()?->getShortName() . '::' . $parameter->getDeclaringFunction()->getName()
                . '() declares $' . $parameter->getName() . ' as "' . $type . '", and ContractTest::valueFor() has no value for it. Give the parameter a type, or a @param tag with one of int, float, bool, string, array or int[].'
            ),
        };
    }

    /**
     * The first type the parameter declares, read as declaredType() reads it: its @param tag, else
     * its native type. Null is skipped, a method is never called with it here
     */
    private static function documentedType(\ReflectionParameter $parameter): string
    {
        $declared = self::declaredType($parameter);
        $types    = array_values(array_diff(self::splitTopLevel(ltrim($declared, '?'), '|'), ['null']));

        return $types[0] ?? $declared;
    }

    /**
     * What the methods of deprecated parameters do wrong, sorted. Each form the parameter takes is
     * used once: a deprecated form must bring the method's notice, any other form must not
     *
     * @param array<string, array<string, array<string, mixed>>> $parameters the parameters of each call, as in SupportedParameters::PARAMETERS
     *
     * @return array<int, string>
     */
    private function deprecatedParameterProblems(array $parameters): array
    {
        $problems = [];
        foreach (self::SENDERS as $class) {
            $reflection = new \ReflectionClass($class);
            $shortName  = $reflection->getShortName();
            $sent       = $this->parametersSent($class);

            foreach ($this->finders($class) as $calls) {
                foreach ($calls as $call) {
                    foreach ($parameters[$call] ?? [] as $name => $parameter) {
                        $deprecated = $parameter['deprecated'] ?? [];

                        // a parameter without a method fails testEveryParameterHasAMethod
                        if ($deprecated === [] || !isset($sent[$name])) {
                            continue;
                        }

                        foreach ($sent[$name] as $method) {
                            if (!self::isTaggedDeprecated($class, $method)) {
                                $problems[] = $shortName . '::' . $method . '() has no @deprecated';
                            }

                            $setter    = new \ReflectionMethod($class, $method);
                            $arguments = array_map([self::class, 'valueFor'], $setter->getParameters());
                            $forms     = ['plain' => $arguments];
                            if (($parameter['list'] ?? false) === true && $arguments !== []) {
                                $forms['list'] = [[$arguments[0]], ...array_slice($arguments, 1)];
                            }

                            foreach ($forms as $form => $values) {
                                self::forgetTheDeprecationNotices();
                                $instance = self::instance($class, new JustimmoNullApi());
                                $notices  = $this->notices(fn () => self::callWithoutTheAnswer(fn () => $instance->$method(...$values)));

                                $isDeprecated = in_array($form, $deprecated, true);
                                $warns        = self::namesTheMethod($notices, $shortName, $method);

                                if ($isDeprecated && !$warns) {
                                    $problems[] = $shortName . '::' . $method . '() emits no notice for the ' . $form . ' form';
                                }
                                if (!$isDeprecated && $warns) {
                                    $problems[] = $shortName . '::' . $method . '() emits a notice for the ' . $form . ' form, which is not deprecated';
                                }
                            }
                        }
                    }
                }
            }
        }

        self::forgetTheDeprecationNotices();

        $problems = array_values(array_unique($problems));
        sort($problems, SORT_STRING);

        return $problems;
    }

    /**
     * Whether one of the notices is the method's own. A notice reads "Since justimmo/php-sdk 1.8.0:
     * BasicDataQuery::findZipCodes() is deprecated …"
     *
     * @param array<int, string> $notices
     */
    private static function namesTheMethod(array $notices, string $shortName, string $method): bool
    {
        $named = array_filter($notices, fn (string $notice) => str_contains($notice, ': ' . $shortName . '::' . $method . '() '));

        return $named !== [];
    }

    /**
     * Whether a call of the method on a new instance emits a notice which names it
     *
     * @param class-string $class
     */
    private function warns(string $class, string $method): bool
    {
        $reflection = new \ReflectionClass($class);
        $setter     = new \ReflectionMethod($class, $method);
        $arguments  = array_map([self::class, 'valueFor'], $setter->getParameters());
        $instance   = self::instance($class, new JustimmoNullApi());

        // a notice is emitted once per process, so one emitted by an earlier test is forgotten first
        self::forgetTheDeprecationNotices();
        $notices = $this->notices(fn () => self::callWithoutTheAnswer(fn () => $instance->$method(...$arguments)));
        self::forgetTheDeprecationNotices();

        return self::namesTheMethod($notices, $reflection->getShortName(), $method);
    }

    private static function basicDataQuery(JustimmoApiInterface $api): BasicDataQuery
    {
        return new BasicDataQuery($api, new BasicDataWrapper(), new BasicDataMapper());
    }

    /**
     * A query or request of the class, with the wrapper and mapper a site gets from it
     *
     * @param class-string $class
     */
    private static function instance(string $class, JustimmoApiInterface $api): RealtyQuery|ProjectQuery|EmployeeQuery|BasicDataQuery|RealtyInquiryRequest
    {
        if ($class === RealtyInquiryRequest::class) {
            return new RealtyInquiryRequest($api, new RealtyInquiryMapper());
        }

        if ($class === BasicDataQuery::class) {
            return self::basicDataQuery($api);
        }

        if ($class === RealtyQuery::class) {
            $mapper = new RealtyMapper();

            return new RealtyQuery($api, new RealtyWrapper($mapper), $mapper);
        }

        if ($class === ProjectQuery::class) {
            $mapper = new ProjectMapper();

            return new ProjectQuery($api, new ProjectWrapper($mapper), $mapper);
        }

        $mapper = new EmployeeMapper();

        return new EmployeeQuery($api, new EmployeeWrapper($mapper), $mapper);
    }

    /**
     * Makes a call for what it sends or emits; the fake answer may make it throw afterwards
     */
    private static function callWithoutTheAnswer(callable $call): void
    {
        try {
            $call();
        } catch (\Throwable) {
            // the call was made before the answer was read
        }
    }

    private static function isTaggedDeprecated(string $class, string $method): bool
    {
        if (!method_exists($class, $method)) {
            return false;
        }

        $reflection = new \ReflectionMethod($class, $method);

        return str_contains((string) $reflection->getDocComment(), '@deprecated');
    }

    /**
     * The deprecation notices the call emits, in order
     *
     * @return array<int, string>
     */
    private function notices(callable $call): array
    {
        $notices = [];

        set_error_handler(function (int $level, string $message) use (&$notices) {
            $notices[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $call();
        } finally {
            restore_error_handler();
        }

        return $notices;
    }
}
