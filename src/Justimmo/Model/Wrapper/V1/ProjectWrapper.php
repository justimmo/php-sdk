<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Project;
use Justimmo\Pager\ListPager;
use Justimmo\Model\Mapper\V1\EmployeeMapper;

class ProjectWrapper extends AbstractWrapper
{
    protected $simpleMapping = array(
        'id',
        'titel',
        'beschreibung',
        'dreizeiler',
        'plz',
        'ort',
        'strasse',
        'hausnummer',
        'in_bau',
        'sonstige_angaben',
        'freitext_1',
        'lage',
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

        if (isset($xml->bilder) && isset($xml->bilder->bild)) {
            $this->mapAttachmentGroup($xml->bilder->bild, $project, 'picture');
        }

        if (isset($xml->plaene) && isset($xml->plaene->bild)) {
            $this->mapAttachmentGroup($xml->plaene->bild, $project, 'picture', 'GRUNDRISS');
        }

        if (isset($xml->dokumente) && isset($xml->dokumente->dokument)) {
            $this->mapAttachmentGroup($xml->dokumente->dokument, $project, 'document');
        }

        if (isset($xml->videos) && isset($xml->videos->video)) {
            $this->mapAttachmentGroup($xml->videos->video, $project, 'video');
        }

        if (isset($xml->bilder360) && isset($xml->bilder360->bild)) {
            $this->mapAttachmentGroup($xml->bilder360->bild, $project, 'picture', 'bilder360');
        }

        if (isset($xml->immobilien->immobilie)) {
            $wrapper = new RealtyWrapper(new RealtyMapper());
            foreach ($xml->immobilien->immobilie as $immobilie) {
                $realty = $wrapper->transformSingle($immobilie->asXML());
                $project->addRealty($realty);
            }
        }

        if (isset($xml->kontaktperson)) {
            $employeeWrapper = new EmployeeWrapper(new EmployeeMapper());
            $contact = $employeeWrapper->transformSingle($xml->kontaktperson->asXML());
            $project->setContact($contact);
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

        if ($list->count() > 0) {
            $list->setMaxPerPage($list->count());
        }
        $list->setNbResults($list->count());

        return $list;
    }

    protected function mapAttachmentGroup(\SimpleXMLElement $xml, Project $project, $type = null, $group = null)
    {
        foreach ($xml as $anhang) {
            $data = (array) $anhang;
            if (array_key_exists('pfad', $data)) {
                $attachment = new Attachment($data['pfad'], $type, $group);
                $attachment->setTitle($this->cast($anhang->titel));
                $project->addAttachment($attachment);
            }
        }
    }

}
