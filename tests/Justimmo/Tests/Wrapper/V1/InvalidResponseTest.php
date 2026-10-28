<?php

namespace Justimmo\Tests\Wrapper\V1;

use Exception;
use Justimmo\Exception\InvalidResponseException;
use Justimmo\Exception\JustimmoException;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;

/**
 * A body which is not XML is an InvalidResponseException on every call that parses one
 *
 * It used to escape as a plain \Exception, after three PHP warnings for a body which was not
 * empty. PHPUnit turns a warning into an error here, so each case also proves that none escapes.
 */
final class InvalidResponseTest extends TestCase
{
    /**
     * @dataProvider brokenBodyProvider
     */
    public function testABodyWhichIsNotXmlThrowsAJustimmoException(string $wrapper, string $method, ?string $body): void
    {
        $wrappers = [
            'realty'    => function () { return new RealtyWrapper(new RealtyMapper()); },
            'project'   => function () { return new ProjectWrapper(new ProjectMapper()); },
            'employee'  => function () { return new EmployeeWrapper(new EmployeeMapper()); },
            'basicData' => function () { return new BasicDataWrapper(); },
        ];

        try {
            $wrappers[$wrapper]()->$method($body);
        } catch (JustimmoException $e) {
            $this->assertInstanceOf(InvalidResponseException::class, $e);
            // a catch (\Exception $e) written against an earlier version still catches it
            $this->assertInstanceOf(Exception::class, $e);
            $this->assertInstanceOf(Exception::class, $e->getPrevious());
            $this->assertStringStartsWith('The api answered with a body which is not valid XML', $e->getMessage());

            return;
        }

        $this->fail($wrapper . ' ' . $method . '() did not throw');
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: ?string}>
     */
    public function brokenBodyProvider(): array
    {
        $calls = [
            ['realty', 'transformList'],
            ['realty', 'transformSingle'],
            ['project', 'transformList'],
            ['project', 'transformSingle'],
            ['employee', 'transformList'],
            ['employee', 'transformSingle'],
            ['basicData', 'transformCountries'],
            ['basicData', 'transformFederalStates'],
            ['basicData', 'transformZipCodes'],
            ['basicData', 'transformRegions'],
            ['basicData', 'transformPoliticalDistricts'],
            ['basicData', 'transformRealtyTypes'],
            ['basicData', 'transformRealtyCategories'],
            ['basicData', 'transformTenant'],
        ];

        $bodies = [
            'an empty body'        => '',
            'no body at all'       => null,
            'an html error page'   => '<html><body>502 Bad Gateway</body>',
            'a body which was cut' => '<justimmo><immobilie><id>1</id>',
        ];

        $cases = [];
        foreach ($calls as [$wrapper, $method]) {
            foreach ($bodies as $name => $body) {
                $cases[$wrapper . ' ' . $method . ', ' . $name] = [$wrapper, $method, $body];
            }
        }

        return $cases;
    }

    /**
     * The first error of the parser says what was wrong with the body
     */
    public function testTheMessageNamesWhatTheParserFound(): void
    {
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('Premature end of data in tag immobilie');

        (new RealtyWrapper(new RealtyMapper()))->transformList('<justimmo><immobilie>');
    }

    /**
     * A framework may turn every PHP warning into an exception. Its exception must not replace ours
     */
    public function testAThrowingErrorHandlerKeepsTheJustimmoException(): void
    {
        set_error_handler(function (int $errno, string $errstr): bool {
            throw new \ErrorException($errstr, 0, $errno);
        });

        try {
            $this->expectException(InvalidResponseException::class);

            (new RealtyWrapper(new RealtyMapper()))->transformList('<justimmo><immobilie>');
        } finally {
            restore_error_handler();
        }
    }
}
