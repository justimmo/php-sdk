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
use Justimmo\Tests\TestCase;

/**
 * Every filter and every sort the api documents has a real method, and every real method is one
 * the api documents. 2.0.0 removes the magic filterBy…() and orderBy…() calls, so a documented name
 * without a method would become unreachable, and a new one in the spec fails here until it has one.
 */
class QueryMethodsTest extends TestCase
{
    /**
     * Documented filters which have no method of their own, with the reason
     */
    const WITHOUT_A_METHOD = [
        // the same column as filter[sort_flaeche], which filterByArea() sends
        RealtyQuery::class => ['flaeche'],
    ];

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
            $sent,
            self::WITHOUT_A_METHOD[$query::class] ?? []
        );

        $this->assertSame([], array_values($missing), self::explain(
            'The api documents these ' . $query->getListCall() . ' filters, but ' . (new \ReflectionClass($query))->getShortName() . ' has no filterBy…() method for them',
            array_values($missing),
            'Add a real filterBy…() method which calls $this->applyFilter(), and the name to the mapper. If it must have none, list it in QueryMethodsTest::WITHOUT_A_METHOD with the reason.',
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
        $shortName  = $reflection->getShortName();

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
