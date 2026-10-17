<?php

namespace Justimmo\Model\Wrapper;

class NullWrapper implements WrapperInterface
{
    /**
     * @param string $data the raw response of the api call
     *
     * @return void
     */
    public function transformList($data)
    {

    }

    /**
     * @param string $data the raw response of the api call
     *
     * @return void
     */
    public function transformSingle($data)
    {

    }
}
