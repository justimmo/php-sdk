<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Condition implements Entity
{
    use Identifiable, Nameable;

    public const FIRST_TIME_OCCUPANCY     = 'first_time_occupancy'; // ERSTBEZUG
    public const IN_NEED_OF_RENOVATION    = 'in_need_of_renovation'; // RENOVIERUNGSBEDUERFTIG
    public const IN_MINT_CONDITION        = 'in_mint_condition'; // NEUWERTIG
    public const COMPLETELY_REFURBISHED   = 'completely_refurbished'; // VOLLSANIERT
    public const COMPLETELY_RENOVATED     = 'completely_renovated'; // VOLLRENOVIERT
    public const DILAPIDATED              = 'dilapidated'; // BAUFAELLIG
    public const AFTER_AGREEMENT          = 'after_agreement'; // NACH_VEREINBARUNG
    public const MODERNIZED               = 'modernized'; // MODERNISIERT
    public const REFINED                  = 'refined'; // GEPFLEGT
    public const SHELL                    = 'shell'; // ROHBAU
    public const GUTTED                   = 'gutted'; // ENTKERNT
    public const PROPERTY_FOR_DEMOLITION  = 'property_for_demolition'; // ABRISSOBJEKT
    public const PLANNED                  = 'planned'; // PROJEKTIERT
    public const PARTIALLY_REFURBISHED    = 'partially_refurbished'; // TEILSANIERT
    public const PARTIALLY_RENOVATED      = 'partially_renovated'; // TEILRENOVIERT
    public const IN_NEED_OF_REFURBISHMENT = 'in_need_of_refurbishment'; // SANIERUNGSBEDUERFTIG

    public function __toString()
    {
        return (string) $this->getName();
    }
}
