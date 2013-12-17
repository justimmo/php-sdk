<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Project;
use Justimmo\Pager\ListPager;

class ProjectWrapper extends AbstractWrapper
{
    protected $simpleMapping = array(
        'id',
        'titel',
        'beschreibung',
        'dreizeiler',
        'plz',
        'ort',
    );

    public function transformSingle($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->projekt)) {
            $xml = $xml->projekt;
        }

        $project = new Project();
        $this->map($this->simpleMapping, $xml, $project);

        if (isset($xml->erstes_bild) && (((string) $xml->erstes_bild) != '')) {
            $project->addAttachment(new Attachment((string) $xml->erstes_bild));
        }

        return $project;
    }

    public function transformList($data)
    {
        $xml = new \SimpleXMLElement($data);

        $list = new ListPager();
        foreach ($xml->projekt as $projekt) {
            $project = $this->transformSingle($projekt->asXML());
            $list->append($project);
        }

        $list->setMaxPerPage($list->count());
        $list->setNbResults($list->count());

        return $list;
    }

}