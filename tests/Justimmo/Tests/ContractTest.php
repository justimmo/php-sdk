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
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
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
    /**
     * Methods of BasicDataQuery which set no parameter
     */
    const BASIC_DATA_NOT_A_PARAMETER = ['__construct', 'clear', 'getParams', 'set'];

    /**
     * The query classes whose find…() methods call the api
     */
    const QUERIES = [RealtyQuery::class, ProjectQuery::class, EmployeeQuery::class, BasicDataQuery::class];

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

                    $query     = self::query($class, new JustimmoNullApi());
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
     * Every parameter of a basic-data endpoint has a method on BasicDataQuery, and every method
     * sends a parameter one of them accepts
     */
    public function testEveryBasicDataParameterHasAMethod(): void
    {
        $methods = [];
        foreach ($this->finders(BasicDataQuery::class) as $called) {
            $methods = [...$methods, ...$called];
        }

        $documented = [];
        foreach (array_unique($methods) as $method) {
            $documented = [...$documented, ...array_keys(SupportedParameters::PARAMETERS[$method] ?? [])];
        }
        $documented = array_values(array_unique($documented));

        $sent = self::basicDataParametersSent();

        // what the api sends by itself counts too, like the culture of JustimmoApi::setCulture()
        $onTheWire = [];
        foreach (array_unique($methods) as $method) {
            $onTheWire = [...$onTheWire, ...$this->parametersOnTheWire($method)];
        }

        $missing = array_values(array_diff($documented, array_keys($sent), $onTheWire));
        $this->assertSame([], $missing, self::explain(
            'The api documents these basic-data parameters, but BasicDataQuery has no method for them',
            $missing,
            'Add a method to BasicDataQuery which sets the parameter.',
            'The api adds a filter or a parameter'
        ));

        $unknown = [];
        foreach ($sent as $parameter => $method) {
            if (!in_array($parameter, $documented, true)) {
                $unknown[] = $method . '() sends ' . $parameter;
            }
        }
        $this->assertSame([], $unknown, self::explain(
            'These BasicDataQuery methods send a parameter which no basic-data endpoint accepts',
            $unknown,
            'If the api dropped the parameter, deprecate the method in a 1.x release and remove it in the next major.',
            'The api drops a filter or a parameter'
        ));
    }

    /**
     * A basic-data parameter whose form the api deprecates has a deprecated method, which warns when
     * that form is used and stays quiet for the others
     */
    public function testADeprecatedBasicDataParameterHasADeprecatedMethod(): void
    {
        $problems = $this->deprecatedBasicDataParameterProblems(SupportedParameters::PARAMETERS);

        $this->assertSame([], $problems, self::explain(
            'The api deprecates these basic-data parameters, but their methods do not follow',
            $problems,
            'Tag the method @deprecated with the replacement, and call $this->triggerDeprecation() with the version of the release. If only the list form is deprecated, warn only when a list is given.',
            'The api deprecates a parameter'
        ));
    }

    /**
     * The json has no deprecated parameter yet, so the check runs against a made-up one: gruppe of
     * the realty features
     */
    public function testTheDeprecatedParameterCheckFindsAMethodWhichDoesNotWarn(): void
    {
        $parameters = SupportedParameters::PARAMETERS;
        $parameters['callRealtyFeatures']['gruppe']['deprecated'] = ['plain'];

        $this->assertSame([
            'filterByFeatureGroupId() emits no notice for the plain form',
            'filterByFeatureGroupId() has no @deprecated',
        ], $this->deprecatedBasicDataParameterProblems($parameters));
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
     * The parameters the method puts on the wire by itself, like the culture, without sdk
     *
     * @return array<int, string>
     */
    private function parametersOnTheWire(string $method): array
    {
        $query = (string) parse_url($this->urlSentBy($method), PHP_URL_QUERY);
        parse_str($query, $parameters);
        unset($parameters['sdk']);

        return array_keys($parameters);
    }

    /**
     * The url the method builds, without a request leaving the machine
     */
    private function urlSentBy(string $method): string
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

        // the fake request answers null, which a method with a return type rejects after the url was built
        self::callWithoutTheAnswer(fn () => $api->$method(...self::arguments($method)));

        return $url;
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
     * Which JustimmoApi methods each find…() method of the query class calls, keyed by the finder
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
            if (!str_starts_with($method->getName(), 'find')) {
                continue;
            }

            $called    = [];
            $api       = $this->recordingApi($called);
            $query     = self::query($class, $api);
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
     * An api which answers as the null api and adds the name of each method called to $called
     *
     * @param array<int, string> $called
     */
    private function recordingApi(array &$called): JustimmoApiInterface
    {
        $null = new JustimmoNullApi();
        $api  = $this->createMock(JustimmoApiInterface::class);

        $interface = new \ReflectionClass(JustimmoApiInterface::class);
        foreach ($interface->getMethods() as $method) {
            $name = $method->getName();

            $api->method($name)->willReturnCallback(function (...$arguments) use ($name, $null, &$called) {
                $called[] = $name;

                return $null->$name(...$arguments);
            });
        }

        return $api;
    }

    /**
     * The parameter each method of BasicDataQuery sets, keyed by the parameter
     *
     * @return array<string, string>
     */
    private static function basicDataParametersSent(): array
    {
        $sent = [];
        $reflection = new \ReflectionClass(BasicDataQuery::class);
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();
            if (str_starts_with($name, 'find') || in_array($name, self::BASIC_DATA_NOT_A_PARAMETER, true)) {
                continue;
            }

            $query = self::basicDataQuery(new JustimmoNullApi());
            $query->$name(1);

            foreach (array_keys($query->getParams()) as $parameter) {
                $sent[$parameter] = $name;
            }
        }

        return $sent;
    }

    /**
     * What the methods of deprecated basic-data parameters do wrong, sorted. Each form the parameter
     * takes is used once: a deprecated form must bring the method's notice, any other form must not
     *
     * @param array<string, array<string, array<string, mixed>>> $parameters the parameters of each call, as in SupportedParameters::PARAMETERS
     *
     * @return array<int, string>
     */
    private function deprecatedBasicDataParameterProblems(array $parameters): array
    {
        $sent = self::basicDataParametersSent();

        $problems = [];
        foreach ($this->finders(BasicDataQuery::class) as $calls) {
            foreach ($calls as $call) {
                foreach ($parameters[$call] ?? [] as $name => $parameter) {
                    $deprecated = $parameter['deprecated'] ?? [];

                    // a parameter without a method fails testEveryBasicDataParameterHasAMethod
                    if ($deprecated === [] || !isset($sent[$name])) {
                        continue;
                    }

                    $method = $sent[$name];
                    if (!self::isTaggedDeprecated(BasicDataQuery::class, $method)) {
                        $problems[] = $method . '() has no @deprecated';
                    }

                    $values = ['plain' => 1];
                    if (($parameter['list'] ?? false) === true) {
                        $values['list'] = [1];
                    }

                    foreach ($values as $form => $value) {
                        self::forgetTheDeprecationNotices();
                        $query   = self::basicDataQuery(new JustimmoNullApi());
                        $notices = $this->notices(fn () => self::callWithoutTheAnswer(fn () => $query->$method($value)));

                        $isDeprecated = in_array($form, $deprecated, true);
                        $warns        = self::namesTheMethod($notices, 'BasicDataQuery', $method);

                        if ($isDeprecated && !$warns) {
                            $problems[] = $method . '() emits no notice for the ' . $form . ' form';
                        }
                        if (!$isDeprecated && $warns) {
                            $problems[] = $method . '() emits a notice for the ' . $form . ' form, which is not deprecated';
                        }
                    }
                }
            }
        }

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

    private static function basicDataQuery(JustimmoApiInterface $api): BasicDataQuery
    {
        return new BasicDataQuery($api, new BasicDataWrapper(), new BasicDataMapper());
    }

    /**
     * A query of the class, with the wrapper and mapper a site gets from it
     *
     * @param class-string $class
     */
    private static function query(string $class, JustimmoApiInterface $api): RealtyQuery|ProjectQuery|EmployeeQuery|BasicDataQuery
    {
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
