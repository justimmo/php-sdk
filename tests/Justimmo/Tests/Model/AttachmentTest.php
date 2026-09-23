<?php
namespace Justimmo\Tests\Model;

use Justimmo\Exception\AttachmentSizeNotFoundException;
use Justimmo\Model\Attachment;
use Justimmo\Tests\TestCase;

class AttachmentTest extends TestCase
{
    public function testCalculateUrl()
    {
        $attachment = new Attachment('http://files.justimmo.at/public/pic/medium/test.jpg');
        $this->assertEquals('http://files.justimmo.at/public/pic/medium/test.jpg', $attachment->calculateUrl('medium'));
        $this->assertEquals('http://files.justimmo.at/public/pic/big/test.jpg', $attachment->calculateUrl('big'));

        $attachment = new Attachment('http://files.justimmo.at/public/video/lq/test.mp4');
        $this->assertEquals('http://files.justimmo.at/public/video/hq/test.mp4', $attachment->calculateUrl('hq'));
        $this->assertEquals('http://files.justimmo.at/public/video/default/test.mp4', $attachment->calculateUrl('default'));

        $attachment = new Attachment('http://files.justimmo.at/public/doc/test.pdf');
        $this->assertEquals('http://files.justimmo.at/public/doc/test.pdf', $attachment->calculateUrl('hq'));
        $this->assertEquals('http://files.justimmo.at/public/doc/test.pdf', $attachment->calculateUrl('default'));
        $this->assertEquals('http://files.justimmo.at/public/doc/test.pdf', $attachment->calculateUrl());
    }

    public function testGetUrlReturnsTheRequestedSize()
    {
        $attachment = new Attachment('http://files.justimmo.at/public/pic/orig/test.jpg');
        $attachment->mergeData(['big' => 'http://files.justimmo.at/public/pic/big/test.jpg']);

        $this->assertEquals('http://files.justimmo.at/public/pic/orig/test.jpg', $attachment->getUrl());
        $this->assertEquals('http://files.justimmo.at/public/pic/big/test.jpg', $attachment->getUrl('big'));
    }

    public function testGetUrlReturnsNullForASizeTheApiDidNotReturn()
    {
        // Project images are returned with only the original size, so asking for
        // a larger one used to emit a php warning next to the null.
        $attachment = new Attachment('http://files.justimmo.at/public/pic/orig/test.jpg');

        $this->assertNull($attachment->getUrl('big'));
    }

    public function testGetUrlOrFailReturnsTheRequestedSize()
    {
        $attachment = new Attachment('http://files.justimmo.at/public/pic/orig/test.jpg');
        $attachment->mergeData(['big' => 'http://files.justimmo.at/public/pic/big/test.jpg']);

        $this->assertEquals('http://files.justimmo.at/public/pic/orig/test.jpg', $attachment->getUrlOrFail());
        $this->assertEquals('http://files.justimmo.at/public/pic/big/test.jpg', $attachment->getUrlOrFail('big'));
    }

    public function testGetUrlOrFailThrowsForASizeTheApiDidNotReturn()
    {
        $attachment = new Attachment('http://files.justimmo.at/public/pic/orig/test.jpg');

        $this->expectException(AttachmentSizeNotFoundException::class);
        $this->expectExceptionMessage('does not provide the size "big"');

        $attachment->getUrlOrFail('big');
    }

    public function testGetVideoPosterUrlReturnsNullForASizeTheApiDidNotReturn()
    {
        $attachment = new Attachment('http://files.justimmo.at/public/video/lq/test.mp4');

        $this->assertEquals('http://files.justimmo.at/public/pic/lq/test.jpg', $attachment->getVideoPosterUrl());
        $this->assertNull($attachment->getVideoPosterUrl('hq'));
    }
}
