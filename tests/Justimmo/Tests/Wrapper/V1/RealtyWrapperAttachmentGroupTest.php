<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;

/**
 * @covers AbstractWrapper::mapAttachmentGroup
 */
class RealtyWrapperAttachmentGroupTest extends TestCase
{
    public function testWithoutOrig()
    {
        $wrapper = new RealtyWrapper(new RealtyMapper());

        $response = $this->getFixtures('v1/realty_detail.xml');
        $realty   = $wrapper->transformSingle($response);

        $attachments = $realty->getAttachments();
        /** @var Attachment $attachment */
        foreach ($attachments as $attachment) {
            if ($attachment->getGroup() === 'LINKS') {
                continue;
            }

            $data = $attachment->getData();
            $this->assertEquals('/public/pic/big', dirname(parse_url($data['orig'])['path']));
            $this->assertEquals('/public/pic/big', dirname(parse_url($data['pfad'])['path']));
            $this->assertEquals('/public/pic/small', dirname(parse_url($data['small'])['path']));
            $this->assertEquals('/public/pic/medium', dirname(parse_url($data['medium'])['path']));
            $this->assertEquals('/public/pic/big2', dirname(parse_url($data['big2'])['path']));
            $this->assertEquals('/public/pic/pdf', dirname(parse_url($data['medium2'])['path']));
            $this->assertEquals('/public/pic/s220x155', dirname(parse_url($data['s220x155'])['path']));
        }
    }

    public function testWithOrig()
    {
        $wrapper = new RealtyWrapper(new RealtyMapper());

        $response = $this->getFixtures('v1/realty_detail_2.xml');
        $realty   = $wrapper->transformSingle($response);

        $attachments = $realty->getAttachments();
        /** @var Attachment $attachment */
        foreach ($attachments as $attachment) {
            if ($attachment->getGroup() === 'LINKS') {
                continue;
            }

            $data = $attachment->getData();
            $this->assertEquals('/public', dirname(parse_url($data['orig'])['path']));
            $this->assertEquals('/public/pic/big', dirname(parse_url($data['pfad'])['path']));
            $this->assertEquals('/public/pic/small', dirname(parse_url($data['small'])['path']));
            $this->assertEquals('/public/pic/medium', dirname(parse_url($data['medium'])['path']));
            $this->assertEquals('/public/pic/big2', dirname(parse_url($data['big2'])['path']));
            $this->assertEquals('/public/pic/pdf', dirname(parse_url($data['medium2'])['path']));
            $this->assertEquals('/public/pic/s220x155', dirname(parse_url($data['s220x155'])['path']));
        }
    }

}
