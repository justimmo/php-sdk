<?php
namespace Justimmo\Model\Query;

interface QueryInterface
{
    public function find();

    public function findOne();

    public function paginate($page = 1, $maxPerPage = 10);

    public function findPk($pk);

    public function clear();
}
