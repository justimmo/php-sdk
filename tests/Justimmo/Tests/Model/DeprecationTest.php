<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\BasicDataMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Tests\TestCase;

/**
 * What 2.0.0 makes protected or removes emits a deprecation notice in 1.6.0, and only a call from
 * outside the SDK does. A method which keeps working stays silent, including where the SDK calls
 * a deprecated one's code itself.
 */
class DeprecationTest extends TestCase
{
    const SINCE = 'Since justimmo/php-sdk 1.6.0: ';

    protected function setUp(): void
    {
        self::forgetTheDeprecationNotices();
    }

    public function testSetFilterAndOrderEmitANotice(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'set() is deprecated and is protected in 2.0.0. Use the method of the parameter, for example setLimit(), setCulture() or a filterBy…() method.',
                self::SINCE . 'filter() is deprecated and is protected in 2.0.0. Use the filterBy…() method of the filter, for example filterByPrice().',
                self::SINCE . 'order() is deprecated and is protected in 2.0.0. Use the orderBy…() method of the column, for example orderByPrice().',
                self::SINCE . 'orderBy() is deprecated and is protected in 2.0.0. Use the orderBy…() method of the column, for example orderByPrice().',
            ],
            $this->notices(fn () => $this->realty()
                ->set('limit', 5)
                ->filter('preis', ['min' => 1])
                ->order('preis')
                ->orderBy('Price'))
        );
    }

    public function testANoticeIsEmittedOncePerRequest(): void
    {
        $notices = $this->notices(function () {
            $this->realty()->set('limit', 5)->set('offset', 10);
            $this->project()->set('limit', 5);
        });

        $this->assertCount(1, $notices);
    }

    public function testAMagicNameEmitsANotice(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'filterByFoo() is deprecated and is removed in 2.0.0. The api does not document this filter.',
                self::SINCE . 'orderByArea() is deprecated and is removed in 2.0.0. The api does not document this sort.',
            ],
            $this->notices(fn () => $this->realty()->filterByFoo('x')->orderByArea())
        );
    }

    public function testTheDeprecatedMethodsEmitANotice(): void
    {
        $this->assertSame(
            [
                self::SINCE . 'RealtyQuery::filterByCreatedAt() is deprecated and is removed in 2.0.0. The api has no created_at filter, so it has never filtered.',
                self::SINCE . 'filterByRealtyCategory() is deprecated and is removed in 2.0.0. Use filterByTag(), which builds the same filter.',
                self::SINCE . 'ProjectQuery::filterByCountryIso2() is deprecated and is removed in 2.0.0. The api has no land_iso2 filter, so it has never filtered.',
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
                ->filterByFederalState(2);
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

    private function realty(): RealtyQuery
    {
        return new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), new RealtyMapper());
    }

    private function project(): ProjectQuery
    {
        return new ProjectQuery(new JustimmoNullApi(), new NullWrapper(), new ProjectMapper());
    }

    private function basicData(): BasicDataQuery
    {
        return new BasicDataQuery(new JustimmoNullApi(), new BasicDataWrapper(), new BasicDataMapper());
    }
}
