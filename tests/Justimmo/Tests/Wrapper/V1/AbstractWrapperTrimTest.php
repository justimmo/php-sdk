<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Tests\TestCase;

/**
 * The api pretty prints some responses, which puts an element's value on its
 * own indented line and makes that whitespace part of the text node.
 */
class AbstractWrapperTrimTest extends TestCase
{
    private function prettyPrintedProject()
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<justimmo>
    <projekt>
        <id>1</id>
        <titel>
            Ein Projekt
        </titel>
        <bilder>
            <bild>
                <titel>bild.jpg</titel>
                <pfad>
                    https://storage.justimmo.at/thumb/abc/bild.jpg
                </pfad>
            </bild>
        </bilder>
    </projekt>
</justimmo>
XML;
    }

    public function testAttachmentUrlsAreUsable()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());
        $project = $wrapper->transformSingle($this->prettyPrintedProject());

        $attachments = $project->getAttachments();
        $this->assertCount(1, $attachments);

        $url = $attachments[0]->getUrl();
        $this->assertEquals('https://storage.justimmo.at/thumb/abc/bild.jpg', $url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
    }

    public function testScalarValuesAreTrimmed()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());
        $project = $wrapper->transformSingle($this->prettyPrintedProject());

        $this->assertEquals('Ein Projekt', $project->getTitle());
    }
}
