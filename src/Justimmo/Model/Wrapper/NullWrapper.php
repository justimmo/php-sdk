<?php

namespace Justimmo\Model\Wrapper;

class NullWrapper implements WrapperInterface
{
    /**
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return void
     */
    public function transformList($data)
    {

    }

    /**
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return void
     */
    public function transformSingle($data)
    {

    }
}
