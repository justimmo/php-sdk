<?php

namespace Justimmo\Model\Wrapper;

interface WrapperInterface
{
    /**
     * transforms the return of a list call into a ListPager
     *
     * @param $data
     *
     * @return mixed
     */
    public function transformList($data);

    /**
     * transforms the return value of a detail call into a model object
     *
     * @param $data
     *
     * @return mixed
     */
    public function transformSingle($data);
}
