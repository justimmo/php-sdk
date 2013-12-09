<?php

namespace Justimmo\Model\Wrapper\v1;

use Justimmo\Model\Wrapper\WrapperInterface;
use Justimmo\Pager\ListPager;

class ObjektListWrapper implements WrapperInterface
{
    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);

        $transformed = new ListPager();
        $transformed->setNbResults((int) $xml->{'query-result'}->count);

        if (isset($xml->immobilie)) {
            foreach ($xml->immobilie as $immobilie) {
                $transformed->append($immobilie);
            }
        }
        $transformed->setMaxPerPage($transformed->count());

        return $transformed;
    }
}
