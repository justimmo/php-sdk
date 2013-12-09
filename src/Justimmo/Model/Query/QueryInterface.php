<?php
namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

interface QueryInterface
{
    public function __construct(JustimmoApiInterface $api, WrapperInterface $listWrapper, WrapperInterface $detailWrapper);

    public function find();

    public function findOne();

    public function paginate($page = 1, $maxPerPage = 10);
}
