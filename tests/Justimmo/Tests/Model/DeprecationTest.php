<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoNullApi;
use Justimmo\Cache\CacheInterface;
use Justimmo\Model\Mapper\NullMapper;
use Justimmo\Model\Mapper\V1\BasicDataMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Project;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\Realty;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;
use Psr\Log\NullLogger;

/**
 * What 2.0.0 makes protected or removes emits a deprecation notice in 1.6.0 or 1.8.0, and only a call from
 * outside the SDK does. A method which keeps working stays silent, including where the SDK calls
 * a deprecated one's code itself.
 */
class DeprecationTest extends TestCase
{
    const SINCE     = 'Since justimmo/php-sdk 1.6.0: ';
    const SINCE_1_8 = 'Since justimmo/php-sdk 1.8.0: ';

    protected function setUp(): void
    {
        self::forgetTheDeprecationNotices();
    }

    public function testSetFilterAndOrderEmitANotice(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::set() is deprecated and is protected in 2.0.0. Use the method of the parameter, for example setLimit(), setCulture() or a filterBy…() method.',
                self::SINCE . 'RealtyQuery::filter() is deprecated and is protected in 2.0.0. Use the filterBy…() method of the filter, for example filterByPrice().',
                self::SINCE . 'RealtyQuery::order() is deprecated and is protected in 2.0.0. Use the orderBy…() method of the column, for example orderByPrice().',
                self::SINCE . 'RealtyQuery::orderBy() is deprecated and is protected in 2.0.0. Use the orderBy…() method of the column, for example orderByPrice().',
            ],
            $this->notices(fn () => $this->realty()
                ->set('limit', 5)
                ->filter('preis', ['min' => 1])
                ->order('preis')
                ->orderBy('Price'))
        );
    }

    public function testANoticeIsEmittedOnce(): void
    {
        $notices = $this->notices(function () {
            $this->realty()->set('limit', 5)->set('offset', 10);
            $this->realty()->set('limit', 5);
            $this->project()->set('limit', 5);
        });

        // once per class and method: the project query's set() is a notice of its own
        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::set() is deprecated and is protected in 2.0.0. Use the method of the parameter, for example setLimit(), setCulture() or a filterBy…() method.',
                self::SINCE . 'ProjectQuery::set() is deprecated and is protected in 2.0.0. Use the method of the parameter, for example setLimit(), setCulture() or a filterBy…() method.',
            ],
            $notices
        );
    }

    public function testAMagicNameEmitsANotice(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::filterByFoo() is deprecated and is removed in 2.0.0. The SDK has no method for this filter.',
                self::SINCE . 'RealtyQuery::orderByArea() is deprecated and is removed in 2.0.0. The SDK has no method for this sort.',
            ],
            $this->notices(fn () => $this->realty()->filterByFoo('x')->orderByArea())
        );
    }

    /**
     * A wire name still reaches a documented filter or sort, and the notice names the method
     * which sends it. A deprecated method is not named, though it sends the same filter
     */
    public function testAWireNameNamesTheMethodToUse(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::filterBypreis() is deprecated and is removed in 2.0.0. Use filterByPrice().',
                self::SINCE . 'RealtyQuery::orderBypreis() is deprecated and is removed in 2.0.0. Use orderByPrice().',
                self::SINCE . 'RealtyQuery::filterBytag_name() is deprecated and is removed in 2.0.0. Use filterByTag().',
                self::SINCE . 'RealtyQuery::filterByflaeche() is deprecated and is removed in 2.0.0. The SDK has no method for this filter.',
            ],
            $this->notices(fn () => $this->realty()
                ->filterBypreis(['min' => 100])
                ->orderBypreis()
                ->filterBytag_name('a')
                ->filterByflaeche(['min' => 50]))
        );
    }

    /**
     * Every filter and sort the api documents, called by its wire name
     *
     * Read from the generated data, so a new filter or sort in the spec is checked without a new
     * line here. A range is called by both of its ends.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public function wireNames(): array
    {
        $cases = [];

        foreach (['realty' => new RealtyMapper(), 'project' => new ProjectMapper()] as $label => $mapper) {
            $query   = $this->query($label);
            $without = QueryMethodsTest::WITHOUT_A_METHOD[$query::class] ?? [];

            foreach ($mapper->getSupportedFilters($query->getListCall()) as $name => $filter) {
                if (in_array($name, $without, true)) {
                    continue;
                }

                $wireNames = [];
                if (in_array('plain', $filter['forms'], true)) {
                    $wireNames[] = $name;
                }
                if (in_array('range', $filter['forms'], true)) {
                    $wireNames[] = $name . '_von';
                    $wireNames[] = $name . '_bis';
                }

                foreach ($wireNames as $wireName) {
                    $cases[$label . ' filterBy' . $wireName] = [$label, 'filterBy' . $wireName];
                }
            }

            $enum = $mapper->getSupportedParameters($query->getListCall())['orderby']['enum'] ?? [];
            foreach ($enum as $column) {
                $cases[$label . ' orderBy' . $column] = [$label, 'orderBy' . $column];
            }
        }

        return $cases;
    }

    /**
     * The notice of a magic call by a documented wire name names a method, and that method sends
     * the same request
     *
     * @dataProvider wireNames
     */
    public function testEveryWireNameNamesAMethodWhichSendsTheSame(string $label, string $magic): void
    {
        $value = str_starts_with($magic, 'orderBy') ? 'desc' : '1';

        $magicQuery = $this->query($label);
        $notices    = $this->notices(fn () => $magicQuery->$magic($value));

        // a value the filter cannot take, like 1 on an enum, adds a notice of strict mode's rules
        $notices = array_values(array_filter($notices, fn (string $notice) => str_contains($notice, '::' . $magic . '()')));

        $this->assertCount(1, $notices);
        $this->assertSame(1, preg_match("/ Use (\\w+)\\((?:\\['(min|max)' => …\\])?\\)\\.$/u", $notices[0], $match), $notices[0]);

        $method   = $match[1];
        $argument = isset($match[2]) ? [$match[2] => $value] : $value;

        $replacementQuery = $this->query($label);
        $replacementQuery->$method($argument);

        $this->assertSame($magicQuery->getParams(), $replacementQuery->getParams());
    }

    /**
     * A sort method is found by the column it sends, whatever the mapper says.
     * RealtyQuery::orderByUpdatedAt() always sends updated_at, also with a mapper which has no
     * sort names of its own
     */
    public function testASortIsFoundByTheColumnItSends(): void
    {
        $nullMapped = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), new NullMapper());

        // a mapper which knows only filter names, as one written before 1.5.0 does
        $legacyMapper = new class () extends NullMapper {
            public function getFilterPropertyName(mixed $modelPropertyName): string
            {
                return $modelPropertyName === 'UpdatedAt' ? 'aktualisiert_am' : (string) $modelPropertyName;
            }
        };
        $legacyMapped = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), $legacyMapper);

        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::orderByupdated_at() is deprecated and is removed in 2.0.0. Use orderByUpdatedAt().',
                self::SINCE . 'RealtyQuery::orderByaktualisiert_am() is deprecated and is removed in 2.0.0. The SDK has no method for this sort.',
            ],
            $this->notices(function () use ($nullMapped, $legacyMapped) {
                $nullMapped->orderByupdated_at();
                $legacyMapped->orderByaktualisiert_am();
            })
        );
    }

    /**
     * Finding the method for the notice runs no method of the query, so a sort of a subclass
     * which takes other arguments is never called
     */
    public function testFindingTheReplacementCallsNoMethodOfTheQuery(): void
    {
        $query = new class (new JustimmoNullApi(), new NullWrapper(), new RealtyMapper()) extends RealtyQuery {
            public function orderByDistance(float $latitude, float $longitude): static
            {
                throw new \LogicException('orderByDistance() was called with ' . $latitude . ', ' . $longitude);
            }
        };

        $notices = $this->notices(fn () => $query->orderByArea('desc'));

        $this->assertCount(1, $notices);
        $this->assertSame('desc', $query->getParams()['ordertype']);
    }

    /**
     * A notice is emitted once per class and method, whatever the mapper of the query. Two mappers
     * of one class which map a name apart give one notice, with the hint of the first
     */
    public function testTwoMappersOfOneClassGiveOneNotice(): void
    {
        $mapperWith = fn (array $names) => new class ($names) extends NullMapper {
            public function __construct(private array $names)
            {
            }

            public function getFilterPropertyName(mixed $modelPropertyName): string
            {
                return $this->names[$modelPropertyName] ?? (string) $modelPropertyName;
            }
        };

        $first  = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), $mapperWith(['Foo' => 'preis', 'Price' => 'preis']));
        $second = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), $mapperWith(['Foo' => 'bar']));

        $notices = $this->notices(function () use ($first, $second) {
            $first->filterByFoo(1);
            $second->filterByFoo(2);
        });

        $this->assertSame(
            [self::SINCE . 'RealtyQuery::filterByFoo() is deprecated and is removed in 2.0.0. Use filterByPrice().'],
            $notices
        );
        $this->assertSame(1, $first->getParams()['filter']['preis']);
        $this->assertSame(2, $second->getParams()['filter']['bar']);
    }

    public function testTheDeprecatedMethodsEmitANotice(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::filterByCreatedAt() is deprecated and is removed in 2.0.0. The api has no created_at filter, so it has never filtered.',
                self::SINCE . 'RealtyQuery::filterByRealtyCategory() is deprecated and is removed in 2.0.0. Use filterByTag(), which builds the same filter.',
                self::SINCE . 'ProjectQuery::filterByCountryIso2() is deprecated and is removed in 2.0.0. The api has no land_iso2 filter, so it has never filtered.',
                self::SINCE . 'ProjectQuery::filterByRealtyCategory() is deprecated and is removed in 2.0.0. Use filterByTag(), which builds the same filter.',
            ],
            $this->notices(function () {
                $this->realty()->filterByCreatedAt(['min' => '2026-01-01'])->filterByRealtyCategory('a');
                $this->project()->filterByCountryIso2('AT')->filterByRealtyCategory('a');
            })
        );
    }

    public function testBasicDataSetEmitsANotice(): void
    {
        $this->assertSame(
            [self::SINCE . 'BasicDataQuery::set() is deprecated and is protected in 2.0.0. Use all(), filterByCountry() or filterByFederalState().'],
            $this->notices(fn () => $this->basicData()->set('alle', 1))
        );
    }

    public function testTheDeprecatedModelMethodsEmitANotice(): void
    {
        $realty  = new Realty();
        $project = new Project();

        $this->assertSame(
            [
                self::SINCE_1_8 . 'Realty::setInfrastructureProvision() is deprecated and is removed in 2.0.0. Use setDevelopmentState().',
                self::SINCE_1_8 . 'Realty::setInfrastructureProvisionCosts() is deprecated and is removed in 2.0.0. Use setDevelopmentCosts().',
                self::SINCE_1_8 . 'Realty::getInfrastructureProvision() is deprecated and is removed in 2.0.0. Use getDevelopmentState() for the state and getDevelopmentCosts() for the costs.',
                self::SINCE_1_8 . 'Realty::getInfrastructureProvisionCosts() is deprecated and is removed in 2.0.0. Use getDevelopmentCosts().',
                self::SINCE_1_8 . 'Project::getUnderConstruction() is deprecated and is removed in 2.0.0. Use isStateBuilding() or getProjectState().',
            ],
            $this->notices(function () use ($realty, $project) {
                $realty->setInfrastructureProvision('VOLLERSCHLOSSEN');
                $realty->setInfrastructureProvisionCosts(12000.5);
                $realty->getInfrastructureProvision();
                $realty->getInfrastructureProvisionCosts();
                $project->getUnderConstruction();
            })
        );
    }

    /**
     * The old getters return what they returned in 1.7.0: the state, or the costs when there is no
     * state; and the costs
     */
    public function testTheDeprecatedRealtyGettersKeepTheirResult(): void
    {
        $realty = new Realty();
        $realty->setDevelopmentCosts(12000.5);

        $this->notices(function () use ($realty) {
            $this->assertSame(12000.5, $realty->getInfrastructureProvision());
            $this->assertSame(12000.5, $realty->getInfrastructureProvisionCosts());

            $realty->setDevelopmentState('VOLLERSCHLOSSEN');
            $this->assertSame('VOLLERSCHLOSSEN', $realty->getInfrastructureProvision());
            $this->assertSame(12000.5, $realty->getInfrastructureProvisionCosts());

            // the old setters write what the new getters read
            $realty->setInfrastructureProvision('TEILERSCHLOSSEN');
            $realty->setInfrastructureProvisionCosts(500.0);
            $this->assertSame('TEILERSCHLOSSEN', $realty->getDevelopmentState());
            $this->assertSame(500.0, $realty->getDevelopmentCosts());
        });
    }

    /**
     * The zip code and region calls of the api are deprecated, like their finders
     */
    public function testTheZipCodeAndRegionCallsEmitANotice(): void
    {
        $api = $this->answeringApi();

        $this->assertSame(
            [
                self::SINCE_1_8 . 'JustimmoApi::callZipCodes() is deprecated and is removed in 2.0.0. Use callPoliticalDistricts().',
                self::SINCE_1_8 . 'JustimmoApi::callRegions() is deprecated and is removed in 2.0.0. Use callPoliticalDistricts().',
            ],
            $this->notices(function () use ($api) {
                $api->callZipCodes();
                $api->callRegions();
            })
        );
    }

    /**
     * A site which calls a finder gets the finder's notice only, not the api call's as well
     */
    public function testTheZipCodeAndRegionFindersEmitOneNoticeEach(): void
    {
        $query = new BasicDataQuery($this->answeringApi(), new BasicDataWrapper(), new BasicDataMapper());

        $this->assertSame(
            [
                self::SINCE_1_8 . 'BasicDataQuery::findZipCodes() is deprecated and is removed in 2.0.0. Use findPoliticalDistricts().',
                self::SINCE_1_8 . 'BasicDataQuery::findRegions() is deprecated and is removed in 2.0.0. Use findPoliticalDistricts().',
            ],
            $this->notices(function () use ($query) {
                $query->findZipCodes();
                $query->findRegions();
            })
        );
    }

    /**
     * The SDK reads an answer without calling a deprecated method, so a site sees only the notices
     * of its own calls
     */
    public function testReadingAnAnswerEmitsNone(): void
    {
        $realtyWrapper  = new RealtyWrapper(new RealtyMapper());
        $projectWrapper = new ProjectWrapper(new ProjectMapper());

        $this->assertSame([], $this->notices(function () use ($realtyWrapper, $projectWrapper) {
            $realtyWrapper->transformSingle($this->getFixtures('v1/synthetic/realty_detail.xml'));
            $projectWrapper->transformSingle($this->getFixtures('v1/synthetic/project_detail.xml'));
        }));
    }

    /**
     * A method deprecated after 1.6.0 names its own version in the notice
     */
    public function testTheNoticeNamesTheVersionGiven(): void
    {
        $model = new DeprecatingModel();

        $notices = $this->notices(fn () => $model->getOld());

        $this->assertSame(
            ['Since justimmo/php-sdk 1.8.0: DeprecatingModel::getOld() is deprecated and is removed in 2.0.0. Use getNew().'],
            $notices
        );
    }

    /**
     * The methods which keep working call the code of set(), filter() and order() themselves
     */
    public function testTheMethodsWhichStayEmitNone(): void
    {
        $this->assertSame([], $this->notices(function () {
            $this->realty()
                ->setLimit(5)
                ->setOffset(10)
                ->setCulture('en')
                ->setPicturesize('small')
                ->allProjectRealties()
                ->preciseAreaSearch()
                ->filterByPrice(['min' => 1])
                ->filterByZipCode('1010')
                ->filterByProjectId(5)
                ->filterByTag('a')
                ->filterByEquipment(1)
                ->orderByPrice('desc')
                ->orderByUpdatedAt();

            $this->project()
                ->all()
                ->allProjectRealties()
                ->onlyRealtyIds()
                ->filterByProjectState('building')
                ->filterByArea(['min' => 50])
                ->orderByCreatedAt();

            $this->basicData()
                ->all(true)
                ->filterByCountry(1)
                ->filterByFederalState(2)
                ->filterByFeatureGroupId(3);
        }));
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

    private function query(string $label): RealtyQuery|ProjectQuery
    {
        return $label === 'realty' ? $this->realty() : $this->project();
    }

    private function realty(): RealtyQuery
    {
        return new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), new RealtyMapper());
    }

    private function project(): ProjectQuery
    {
        return new ProjectQuery(new JustimmoNullApi(), new NullWrapper(), new ProjectMapper());
    }

    /**
     * A real JustimmoApi, so the notice names it, which answers every call with an empty list
     * from its cache
     */
    private function answeringApi(): JustimmoApi
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('generateCacheKey')->willReturn('key');
        $cache->method('get')->willReturn('<justimmo></justimmo>');

        return new JustimmoApi('username', 'password', new NullLogger(), $cache);
    }

    private function basicData(): BasicDataQuery
    {
        return new BasicDataQuery(new JustimmoNullApi(), new BasicDataWrapper(), new BasicDataMapper());
    }
}
