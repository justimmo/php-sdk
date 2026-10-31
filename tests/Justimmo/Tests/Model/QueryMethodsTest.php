<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
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
     * @return array<string, array{0: AbstractQuery, 1: RealtyMapper|ProjectMapper}>
     */
    public function queries(): array
    {
        $realty  = new RealtyMapper();
        $project = new ProjectMapper();

        return [
            'realty'  => [new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), $realty), $realty],
            'project' => [new ProjectQuery(new JustimmoNullApi(), new NullWrapper(), $project), $project],
        ];
    }

    /**
     * @dataProvider queries
     */
    public function testEveryDocumentedFilterHasAMethod(AbstractQuery $query, RealtyMapper|ProjectMapper $mapper): void
    {
        $sent = array_map([$mapper, 'getFilterPropertyName'], $this->methods($query, 'filterBy'));

        $missing = array_diff(
            array_keys($mapper->getSupportedFilters($query->getListCall())),
            $sent,
            self::WITHOUT_A_METHOD[$query::class] ?? []
        );

        $this->assertSame([], array_values($missing));
    }

    /**
     * @dataProvider queries
     */
    public function testEveryDocumentedSortHasAMethod(AbstractQuery $query, RealtyMapper|ProjectMapper $mapper): void
    {
        $sorted = array_map([$mapper, 'getOrderPropertyName'], $this->methods($query, 'orderBy'));
        $enum   = $mapper->getSupportedParameters($query->getListCall())['orderby']['enum'] ?? [];

        $this->assertNotSame([], $enum);
        $this->assertSame([], array_values(array_diff($enum, $sorted)));
    }

    /**
     * A method for a name the api does not document is allowed only on its way out
     *
     * @dataProvider queries
     */
    public function testEveryMethodIsDocumentedOrDeprecated(AbstractQuery $query, RealtyMapper|ProjectMapper $mapper): void
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

        $this->assertSame([], $undocumented);
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

    private function isDeprecated(AbstractQuery $query, string $method): bool
    {
        $reflection = new \ReflectionMethod($query, $method);
        $docComment = (string) $reflection->getDocComment();

        return str_contains($docComment, '@deprecated');
    }
}
