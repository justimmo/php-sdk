<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class EmployeeQuery
 * @package Justimmo\Model
 *
 * @method Employee findPk($pk)
 */
class EmployeeQuery extends AbstractQuery
{
    /**
     * True while findOne() or paginate() of the SDK runs, so the limit and the offset they set
     * emit no notice: the site did not call setLimit() or setOffset()
     */
    private bool $sdkCall = false;

    /**
     * @inheritdoc
     */
    public function getListCall()
    {
        return 'callEmployeeList';
    }

    /**
     * @inheritdoc
     */
    public function getDetailCall()
    {
        return 'callEmployeeDetail';
    }

    /**
     * @inheritdoc
     */
    public function getIdsCall()
    {
        return 'callEmployeeIds';
    }

    /**
     * Read only with a mapper which has no api json. The team detail takes the picture size only.
     * The culture of a query is not carried over, since the api documents none for it
     *
     * @return array<int, string>
     */
    protected function getDetailParams(): array
    {
        return ['picturesize'];
    }

    /**
     * @param int|float|string $limit
     *
     * @return $this
     *
     * @deprecated since 1.8.0, removed in 2.0.0. The team endpoints take no limit
     */
    public function setLimit($limit)
    {
        if (!$this->sdkCall) {
            $this->triggerDeprecation('setLimit', 'setLimit() is deprecated and is removed in 2.0.0. The team endpoints take no limit.', '1.8.0');
        }

        return parent::setLimit($limit);
    }

    /**
     * @param int|float|string $offset
     *
     * @return $this
     *
     * @deprecated since 1.8.0, removed in 2.0.0. The team endpoints take no offset
     */
    public function setOffset($offset)
    {
        if (!$this->sdkCall) {
            $this->triggerDeprecation('setOffset', 'setOffset() is deprecated and is removed in 2.0.0. The team endpoints take no offset.', '1.8.0');
        }

        return parent::setOffset($offset);
    }

    /**
     * @return $this
     *
     * @deprecated since 1.8.0, removed in 2.0.0. The team endpoints take no culture
     */
    public function setCulture(string $culture): static
    {
        $this->triggerDeprecation('setCulture', 'setCulture() is deprecated and is removed in 2.0.0. The team endpoints take no culture.', '1.8.0');

        return parent::setCulture($culture);
    }

    /**
     * @param int $page
     * @param int $maxPerPage
     *
     * @return \Justimmo\Pager\ListPager
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     *
     * @deprecated since 1.8.0, removed in 2.0.0. The team endpoints take no limit and no offset. Use find()
     */
    public function paginate($page = 1, $maxPerPage = 10)
    {
        $this->triggerDeprecation('paginate', 'paginate() is deprecated and is removed in 2.0.0. The team endpoints take no limit and no offset, so every page holds every employee. Use find().', '1.8.0');

        $this->sdkCall = true;
        try {
            $pager = parent::paginate($page, $maxPerPage);
        } finally {
            $this->sdkCall = false;
        }

        return $pager;
    }

    /**
     * @return \Justimmo\Model\Employee|null
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function findOne()
    {
        $this->sdkCall = true;
        try {
            /** @var \Justimmo\Model\Employee|null $employee */
            $employee = parent::findOne();
        } finally {
            $this->sdkCall = false;
        }

        return $employee;
    }
}
