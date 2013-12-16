<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Wrapper\WrapperInterface;
use Justimmo\Pager\ListPager;

class RealtyListWrapper implements WrapperInterface
{
    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);
        $singleTransformer = new RealtyWrapper();

        $transformed = new ListPager();
        $transformed->setNbResults((int) $xml->{'query-result'}->count);

        if (isset($xml->immobilie)) {
            foreach ($xml->immobilie as $immobilie) {
                $objekt = $singleTransformer->transform($immobilie->asXML());
                $transformed->append($objekt);
            }
        }
        $transformed->setMaxPerPage($transformed->count());

        return $transformed;
    }
}
