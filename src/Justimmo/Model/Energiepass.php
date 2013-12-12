<?php

namespace Justimmo\Model;

class Energiepass
{
    /**
     * @var string
     */
    protected $epart;

    /**
     * @var \DateTime
     */
    protected $gueltigBis = null;

    /**
     * @var double
     */
    protected $hwbWert = null;

    /**
     * @var string
     */
    protected $hwbKlasse = null;

    /**
     * @var double
     */
    protected $fgeeWert = null;

    /**
     * @var string
     */
    protected $fgeeKlasse = null;

    /**
     * @param mixed $epart
     *
     * @return $this
     */
    public function setEpart($epart)
    {
        $this->epart = $epart;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEpart()
    {
        return $this->epart;
    }

    /**
     * @param null $fgeeKlasse
     *
     * @return $this
     */
    public function setFgeeKlasse($fgeeKlasse)
    {
        $this->fgeeKlasse = $fgeeKlasse;

        return $this;
    }

    /**
     * @return null
     */
    public function getFgeeKlasse()
    {
        return $this->fgeeKlasse;
    }

    /**
     * @param null $fgeeWert
     *
     * @return $this
     */
    public function setFgeeWert($fgeeWert)
    {
        $this->fgeeWert = $fgeeWert;

        return $this;
    }

    /**
     * @return null
     */
    public function getFgeeWert()
    {
        return $this->fgeeWert;
    }

    /**
     * @param null $gueltigBis
     *
     * @return $this
     */
    public function setGueltigBis($gueltigBis)
    {
        $this->gueltigBis = $gueltigBis;

        return $this;
    }

    /**
     * @return null
     */
    public function getGueltigBis()
    {
        return $this->gueltigBis;
    }

    /**
     * @param null $hwbKlasse
     *
     * @return $this
     */
    public function setHwbKlasse($hwbKlasse)
    {
        $this->hwbKlasse = $hwbKlasse;

        return $this;
    }

    /**
     * @return null
     */
    public function getHwbKlasse()
    {
        return $this->hwbKlasse;
    }

    /**
     * @param null $hwbWert
     *
     * @return $this
     */
    public function setHwbWert($hwbWert)
    {
        $this->hwbWert = $hwbWert;

        return $this;
    }

    /**
     * @return null
     */
    public function getHwbWert()
    {
        return $this->hwbWert;
    }

}
