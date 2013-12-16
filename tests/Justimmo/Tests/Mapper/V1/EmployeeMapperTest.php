<?php
namespace Justimmo\Tests\Mapper\V1;

use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Tests\TestCase;

class EmployeeMapperTest extends TestCase
{
    /**
     * @var EmployeeMapper
     */
    protected $mapper;

    public function setUp()
    {
        $this->mapper = new EmployeeMapper();
    }

    public function testGetProperty()
    {
        $this->assertEquals('firstName', $this->mapper->getProperty('vorname'));
        $this->assertEquals('test', $this->mapper->getProperty('test'));
    }

    public function testGetSetter()
    {
        $this->assertEquals('setFirstName', $this->mapper->getSetter('vorname'));
        $this->assertEquals('setTest', $this->mapper->getSetter('test'));
    }

    public function testGetType()
    {
        $this->assertEquals('int', $this->mapper->getType('id'));
        $this->assertEquals('string', $this->mapper->getType('test'));
    }

    public function testGetFilterProperty()
    {
        $mapper = new RealtyMapper();
        $this->assertEquals('preis', $mapper->getFilterPropertyName('Price'));
        $this->assertEquals('Test', $mapper->getFilterPropertyName('Test'));
    }
}
