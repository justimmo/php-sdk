<?php

namespace Justimmo\Model\Wrapper;

interface WrapperInterface
{
    /**
     * transforms the return of a list call into a ListPager
     *
     * @param string $data the raw response of the api call
     *
     * @return mixed
     */
    public function transformList($data);

    /**
     * transforms the return value of a detail call into a model object
     *
     * @param string $data the raw response of the api call
     *
     * @return mixed
     */
    public function transformSingle($data);
}
