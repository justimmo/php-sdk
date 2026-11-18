<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Tests\MockJustimmoApi;
use Justimmo\Tests\TestCase;

class EmployeeQueryTest extends TestCase
{
    private function getQuery($api = null)
    {
        $mapper = new EmployeeMapper();
        return new EmployeeQuery($api ?: new JustimmoNullApi(), new EmployeeWrapper($mapper), $mapper);
    }

    public function testFindIds()
    {
        $api = new MockJustimmoApi(['employeeIds' => $this->getFixtures('v1/employee_ids.json')]);
        $query = $this->getQuery($api);

        $this->assertEquals([
            1,
            2,
            4,
            5,
        ], $query->findIds());
    }

    /**
     * An empty answer has no first employee: findOne() returns null, and no PHP warning
     */
    public function testFindOneOnAnEmptyAnswerReturnsNullWithoutAWarning(): void
    {
        $query    = $this->getQuery();
        $warnings = [];

        set_error_handler(function (int $level, string $message) use (&$warnings) {
            $warnings[] = $message;

            return true;
        }, E_WARNING | E_NOTICE);

        try {
            $employee = $query->findOne();
        } finally {
            restore_error_handler();
        }

        $this->assertNull($employee);
        $this->assertSame([], $warnings);
    }

    /**
     * The team endpoints take no limit and no offset, so setLimit() and setOffset() are deprecated
     * here. findOne() and paginate() use them inside, and a site gets no notice for that call
     */
    public function testTheSdkOwnCallsOfTheDeprecatedSettersEmitNoNotice(): void
    {
        $this->assertSame([], $this->noticesOf(fn (EmployeeQuery $query) => $query->findOne()));

        $notices = $this->noticesOf(fn (EmployeeQuery $query) => $query->paginate(2, 5));
        $this->assertCount(1, $notices);
        $this->assertStringContainsString('EmployeeQuery::paginate() is deprecated', $notices[0]);

        $notices = $this->noticesOf(fn (EmployeeQuery $query) => $query->setLimit(5));
        $this->assertCount(1, $notices);
        $this->assertStringContainsString('EmployeeQuery::setLimit() is deprecated', $notices[0]);
    }

    /**
     * The notices are muted only while findOne() or paginate() runs: a site's own call after them
     * still gets its notice
     */
    public function testASiteCallAfterFindOneOrPaginateEmitsItsNotice(): void
    {
        $notices = $this->noticesOf(function (EmployeeQuery $query) {
            $query->findOne();
            $query->setLimit(5);
        });
        $this->assertCount(1, $notices);
        $this->assertStringContainsString('EmployeeQuery::setLimit() is deprecated', $notices[0]);

        $notices = $this->noticesOf(function (EmployeeQuery $query) {
            $query->paginate(2, 5);
            $query->setOffset(5);
        });
        $this->assertCount(2, $notices);
        $this->assertStringContainsString('EmployeeQuery::paginate() is deprecated', $notices[0]);
        $this->assertStringContainsString('EmployeeQuery::setOffset() is deprecated', $notices[1]);
    }

    /**
     * The deprecation notices a call on a new query emits
     *
     * @return array<int, string>
     */
    private function noticesOf(callable $call): array
    {
        self::forgetTheDeprecationNotices();

        $query   = $this->getQuery();
        $notices = [];

        set_error_handler(function (int $level, string $message) use (&$notices) {
            $notices[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $call($query);
        } finally {
            restore_error_handler();
            self::forgetTheDeprecationNotices();
        }

        return $notices;
    }
}
