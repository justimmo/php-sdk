# Realty Inquiry
You can post data directly to Justimmo via the RealtyInquiry object. This data will be seen by the Justimmo user for further contact
``` php

use Justimmo\Request\RealtyInquiryRequest;
use Justimmo\Model\Mapper\V1\RealtyInquiryMapper;

$rq = new RealtyInquiryRequest($api, new RealtyInquiryMapper());
$rq->setRealtyId(12345)
    ->setFirstName('John')
    ->setLastName('Doe')
    ->setEmail('john.doe@test.at')
    ->setMessage('The message what should be sent to the contact person of the realty')
    ->send();
```
