<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\AbstractMapper;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;
use Justimmo\Tests\DocumentedTypes;
use Justimmo\Tests\TestCase;

/**
 * Every filter and every sort the api documents has a real method, and every real method is one
 * the api documents. 2.0.0 removes the magic filterBy…() and orderBy…() calls, so a documented name
 * without a method would become unreachable, and a new one in the spec fails here until it has one.
 */
class QueryMethodsTest extends TestCase
{
    use DocumentedTypes;

    /**
     * @return array<string, array{0: AbstractQuery, 1: AbstractMapper}>
     */
    public function queries(): array
    {
        $realty   = new RealtyMapper();
        $project  = new ProjectMapper();
        $employee = new EmployeeMapper();

        return [
            'realty'   => [new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), $realty), $realty],
            'project'  => [new ProjectQuery(new JustimmoNullApi(), new NullWrapper(), $project), $project],
            'employee' => [new EmployeeQuery(new JustimmoNullApi(), new NullWrapper(), $employee), $employee],
        ];
    }

    /**
     * @dataProvider queries
     */
    public function testEveryDocumentedFilterHasAMethod(AbstractQuery $query, AbstractMapper $mapper): void
    {
        $sent = array_map([$mapper, 'getFilterPropertyName'], $this->methods($query, 'filterBy'));

        $missing = array_diff(
            array_keys($mapper->getSupportedFilters($query->getListCall())),
            $sent
        );

        $this->assertSame([], array_values($missing), self::explain(
            'The api documents these ' . $query->getListCall() . ' filters, but ' . (new \ReflectionClass($query))->getShortName() . ' has no filterBy…() method for them',
            array_values($missing),
            'Add a real filterBy…() method which calls $this->applyFilter(), and the name to the mapper.',
            'The api adds a filter or a parameter'
        ));
    }

    /**
     * @dataProvider queries
     */
    public function testEveryDocumentedSortHasAMethod(AbstractQuery $query, AbstractMapper $mapper): void
    {
        $sorted     = array_map([$mapper, 'getOrderPropertyName'], $this->methods($query, 'orderBy'));
        $parameters = $mapper->getSupportedParameters($query->getListCall());
        $enum       = $parameters['orderby']['enum'] ?? [];

        // an endpoint without orderby has nothing to sort by, like the employee list today
        if (isset($parameters['orderby'])) {
            $this->assertNotSame([], $enum, $query->getListCall() . ' documents orderby without an enum. Check data/sdk-supported-parameters.json');
        }

        $missing = array_values(array_diff($enum, $sorted));
        $this->assertSame([], $missing, self::explain(
            'The api documents these sort columns, but ' . (new \ReflectionClass($query))->getShortName() . ' has no orderBy…() method for them',
            $missing,
            'Add a real orderBy…() method which calls $this->setOrder(), and the column to the mapper.',
            'The api adds a filter or a parameter'
        ));
    }

    /**
     * A method for a name the api does not document is allowed only on its way out
     *
     * @dataProvider queries
     */
    public function testEveryMethodIsDocumentedOrDeprecated(AbstractQuery $query, AbstractMapper $mapper): void
    {
        $filters = $mapper->getSupportedFilters($query->getListCall());
        $enum    = $mapper->getSupportedParameters($query->getListCall())['orderby']['enum'] ?? [];

        $undocumented = [];
        foreach ($this->methods($query, 'filterBy') as $property) {
            if (!isset($filters[$mapper->getFilterPropertyName($property)]) && !$this->isDeprecated($query, 'filterBy' . $property)) {
                $undocumented[] = 'filterBy' . $property;
            }
        }
        foreach ($this->methods($query, 'orderBy') as $property) {
            if (!in_array($mapper->getOrderPropertyName($property), $enum, true) && !$this->isDeprecated($query, 'orderBy' . $property)) {
                $undocumented[] = 'orderBy' . $property;
            }
        }

        $this->assertSame([], $undocumented, self::explain(
            'These methods send a filter or a sort column the api does not document',
            $undocumented,
            'If the api dropped it, tag the method @deprecated and call $this->triggerDeprecation() in a 1.x release; the next major removes it.',
            'The api drops a filter or a parameter'
        ));
    }

    /**
     * A filter whose form the api deprecates has a deprecated method, which warns when that form is
     * used and stays quiet for the others
     *
     * @dataProvider queries
     */
    public function testADeprecatedFilterHasADeprecatedMethod(AbstractQuery $query, AbstractMapper $mapper): void
    {
        $missing = $this->deprecatedFilterProblems($query, $mapper);

        $this->assertSame([], $missing, self::explain(
            'The api deprecates these filters, but their methods are not deprecated',
            $missing,
            'Tag the method @deprecated with the replacement, and call $this->triggerDeprecation() with the version of the release. If only one form is deprecated, warn only when that form is used.',
            'The api deprecates a filter'
        ));
    }

    /**
     * The json has no deprecated filter yet, so the check runs against made-up deprecations: the
     * range form of preis and the list form of plz
     */
    public function testTheDeprecatedFilterCheckFindsAMethodWhichDoesNotFollow(): void
    {
        $mapper = new class () extends RealtyMapper {
            public function getSupportedFilters(string $call): array
            {
                $filters = parent::getSupportedFilters($call);

                $filters['preis']['deprecated'] = ['range'];
                $filters['plz']['deprecated']   = ['list'];

                return $filters;
            }
        };

        // filterByPrice() stays as it is. filterByZipCode() warns for every form, not only the list
        $query = new class (new JustimmoNullApi(), new NullWrapper(), $mapper) extends RealtyQuery {
            /**
             * @deprecated a made-up deprecation
             */
            public function filterByZipCode(mixed $value): static
            {
                $this->triggerDeprecation('filterByZipCode', 'filterByZipCode() is deprecated.', '1.8.0');

                return parent::filterByZipCode($value);
            }
        };

        $this->assertSame([
            'filterByPrice() emits no notice for the range form',
            'filterByPrice() has no @deprecated',
            'filterByZipCode() emits a notice for the plain form, which is not deprecated',
            'filterByZipCode() emits a notice for the range form, which is not deprecated',
        ], $this->deprecatedFilterProblems($query, $mapper));
    }

    /**
     * Every filter method matches its filter in the json: its @param offers each form the json
     * allows, and for each type the @param names, the method sends a value of the json's type
     *
     * @dataProvider queries
     */
    public function testEveryFilterMethodMatchesTheTypeOfItsFilter(AbstractQuery $query, AbstractMapper $mapper): void
    {
        $problems = $this->filterTypeProblems($query, $mapper);

        $this->assertSame([], $problems, self::explain(
            'These ' . (new \ReflectionClass($query))->getShortName() . ' filter methods do not match the type of their filter in the json',
            $problems,
            'Make the @param say every form the json allows. Where a method sends a type the json does not have, change the @param, or convert the value before it is sent.',
            'The api changes the type of a filter or a parameter'
        ));
    }

    /**
     * The check above, run against a json in which miete takes a string and objektart_id a range,
     * finds filterByRent(), which sends 1 for true, and filterByRealtyTypeId(), which offers no range
     */
    public function testTheFilterTypeCheckFindsAMethodWhichDoesNotMatch(): void
    {
        $mapper = new class () extends RealtyMapper {
            public function getSupportedFilters(string $call): array
            {
                $filters = parent::getSupportedFilters($call);

                $filters['miete']['type']         = 'string';
                $filters['objektart_id']['forms'] = ['plain', 'range'];

                return $filters;
            }
        };

        $problems = $this->filterTypeProblems(new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), $mapper), $mapper);

        $this->assertContains('RealtyQuery::filterByRent() with true sends filter[miete] = 1, the json says string', $problems);
        $this->assertContains('RealtyQuery::filterByRealtyTypeId() does not offer the range form of filter[objektart_id] in its @param', $problems);
    }

    /**
     * Every orderBy…() method treats the direction alike: a missing one falls back to asc, any
     * other is passed on as written
     *
     * @dataProvider queries
     */
    public function testEverySortTreatsTheDirectionAlike(AbstractQuery $query, AbstractMapper $mapper): void
    {
        $directions = [
            'null'  => [null, 'asc'],
            'empty' => ['', 'asc'],
            'asc'   => ['asc', 'asc'],
            'desc'  => ['desc', 'desc'],
            'DESC'  => ['DESC', 'DESC'],
        ];

        $expected = [];
        $sent     = [];
        foreach ($this->methods($query, 'orderBy') as $property) {
            foreach ($directions as $label => [$given, $ordertype]) {
                $query->{'orderBy' . $property}($given);

                $expected['orderBy' . $property . ' ' . $label] = $ordertype;
                $sent['orderBy' . $property . ' ' . $label]     = $query->getParams()['ordertype'];
            }
        }

        // a query whose endpoint sorts has orderBy…() methods, so the loop above ran
        $parameters = $mapper->getSupportedParameters($query->getListCall());
        if (isset($parameters['orderby'])) {
            $this->assertNotSame([], $sent);
        }
        $this->assertSame($expected, $sent);
    }

    /**
     * The property names of the public methods which start with the prefix
     *
     * @return array<int, string>
     */
    private function methods(AbstractQuery $query, string $prefix): array
    {
        $class   = new \ReflectionClass($query);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

        $names = [];
        foreach ($methods as $method) {
            if (preg_match('/^' . $prefix . '(\w+)$/', $method->getName(), $match)) {
                $names[] = $match[1];
            }
        }

        return $names;
    }

    /**
     * What the methods of deprecated filters do wrong, sorted. Each form the filter takes is used
     * once: a deprecated form must bring the method's notice, any other form must not
     *
     * @return array<int, string>
     */
    private function deprecatedFilterProblems(AbstractQuery $query, AbstractMapper $mapper): array
    {
        $filters    = $mapper->getSupportedFilters($query->getListCall());
        $reflection = new \ReflectionClass($query);

        // a notice of an anonymous class names the class it extends, like the made-up query of
        // testTheDeprecatedFilterCheckFindsAMethodWhichDoesNotFollow()
        while ($reflection->isAnonymous() && $reflection->getParentClass() !== false) {
            $reflection = $reflection->getParentClass();
        }
        $shortName = $reflection->getShortName();

        $problems = [];
        foreach ($this->methods($query, 'filterBy') as $property) {
            $method     = 'filterBy' . $property;
            $filter     = $filters[$mapper->getFilterPropertyName($property)] ?? [];
            $deprecated = $filter['deprecated'] ?? [];
            if ($deprecated === []) {
                continue;
            }

            if (!$this->isDeprecated($query, $method)) {
                $problems[] = $method . '() has no @deprecated';
            }

            foreach (self::formValues($filter) as $form => $value) {
                $isDeprecated = in_array($form, $deprecated, true);
                $warns        = $this->warns($query, $shortName, $method, $value);

                if ($isDeprecated && !$warns) {
                    $problems[] = $method . '() emits no notice for the ' . $form . ' form';
                }
                if (!$isDeprecated && $warns) {
                    $problems[] = $method . '() emits a notice for the ' . $form . ' form, which is not deprecated';
                }
            }
        }

        sort($problems, SORT_STRING);

        return $problems;
    }

    /**
     * A value for each form the filter takes, keyed by the form: plain, list and range
     *
     * @param array<string, mixed> $filter
     *
     * @return array<string, mixed>
     */
    private static function formValues(array $filter): array
    {
        $byType = ['string' => 'a', 'integer' => 1, 'number' => 1, 'boolean' => 1];
        $value  = $filter['enum'][0] ?? $byType[$filter['type']] ?? 1;

        $values = [];
        if (in_array('plain', $filter['forms'], true)) {
            $values['plain'] = $value;
        }
        if (($filter['list'] ?? false) === true) {
            $values['list'] = [$value];
        }
        if (in_array('range', $filter['forms'], true)) {
            $values['range'] = ['min' => $value];
        }

        return $values;
    }

    /**
     * What the filter methods of the query do not match in the json, sorted. A method whose filter
     * the json does not have is left to testEveryMethodIsDocumentedOrDeprecated()
     *
     * @return array<int, string>
     */
    private function filterTypeProblems(AbstractQuery $query, AbstractMapper $mapper): array
    {
        $shortName = (new \ReflectionClass($query))->getShortName();
        $filters   = $mapper->getSupportedFilters($query->getListCall());

        $problems = [];
        foreach ($this->methods($query, 'filterBy') as $property) {
            $key = $mapper->getFilterPropertyName($property);
            if (!isset($filters[$key])) {
                continue;
            }

            $filter    = $filters[$key];
            $method    = 'filterBy' . $property;
            $name      = $shortName . '::' . $method . '()';
            $arguments = (new \ReflectionMethod($query, $method))->getParameters();
            if ($arguments === []) {
                $problems[] = $name . ' takes no value';
                continue;
            }

            $offered = self::offeredForms(self::declaredType($arguments[0]));

            foreach (self::documentedForms($filter) as $form) {
                if (!isset($offered[$form])) {
                    $problems[] = $name . ' does not offer the ' . $form . ' form of filter[' . $key . '] in its @param';
                }
            }

            foreach ($offered as $form => $types) {
                foreach ($types as $type) {
                    $sample = self::sampleOf($type, $filter);
                    if ($sample === null) {
                        $problems[] = $name . ' names ' . self::unclearType($form, $type) . ' in its @param, which does not say what it sends';
                        continue;
                    }

                    $value = self::valueOfForm($form, $sample);
                    $sent  = $this->filterSent($query, $method, $value, $key);
                    if ($sent === []) {
                        $problems[] = $name . ' with ' . self::describe($value) . ' sends no filter[' . $key . ']';
                        continue;
                    }

                    foreach ($sent as $sentKey => $sentValue) {
                        $problem = self::sentProblem($sentValue, $filter, $sentKey !== $key);
                        if ($problem !== null) {
                            $problems[] = $name . ' with ' . self::describe($value) . ' sends filter[' . $sentKey . '] = ' . self::describe($sentValue) . ', ' . $problem;
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
     * What the method puts into filter[key], filter[key_von] and filter[key_bis], called on the
     * emptied query. A notice it emits, or an exception it throws, is not what is checked here
     *
     * @return array<string, mixed>
     */
    private function filterSent(AbstractQuery $query, string $method, mixed $value, string $key): array
    {
        self::forgetTheDeprecationNotices();
        $query->clear();

        set_error_handler(fn () => true, E_USER_DEPRECATED);
        try {
            $query->$method($value);
        } catch (\Throwable) {
            // what was set before it threw is still read
        } finally {
            restore_error_handler();
        }
        self::forgetTheDeprecationNotices();

        $filters = $query->getParams()['filter'] ?? [];
        $query->clear();

        return array_intersect_key($filters, array_flip([$key, $key . '_von', $key . '_bis']));
    }

    /**
     * Whether the method emits its own deprecation notice for the value. Other notices, like the
     * one for a wrong value, do not count
     */
    private function warns(AbstractQuery $query, string $shortName, string $method, mixed $value): bool
    {
        self::forgetTheDeprecationNotices();
        $query->clear();

        $notices = [];
        set_error_handler(function (int $level, string $message) use (&$notices) {
            $notices[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $query->$method($value);
        } catch (\Throwable) {
            // a value the method rejects has shown by now whether it warns
        } finally {
            restore_error_handler();
        }

        // a notice reads "Since justimmo/php-sdk 1.8.0: RealtyQuery::filterByPrice() is deprecated …"
        $named = array_filter($notices, fn (string $notice) => str_contains($notice, ': ' . $shortName . '::' . $method . '() '));

        return $named !== [];
    }

    private function isDeprecated(AbstractQuery $query, string $method): bool
    {
        $reflection = new \ReflectionMethod($query, $method);
        $docComment = (string) $reflection->getDocComment();

        return str_contains($docComment, '@deprecated');
    }
}
