<?php
namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

interface QueryInterface
{
    public function find();

    public function findOne();

    public function paginate($page = 1, $maxPerPage = 10);

    public function findPk($pk);

    public function clear();
}
