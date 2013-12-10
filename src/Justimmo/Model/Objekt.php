<?php

namespace Justimmo\Model;

class Objekt
{
    protected $id;

    protected $objektnummer;

    protected $titel = null;

    protected $dreizeiler = null;

    protected $naehe = null;

    protected $objektbeschreibung = null;

    protected $etage = null;

    protected $tuernummer = null;

    protected $plz = null;

    protected $ort = null;

    protected $kaufpreis = null;

    protected $gesamtmiete = null;

    protected $nutzflaeche = null;

    protected $grundflaeche = null;

    protected $projektId = null;

    protected $status = null;

    protected $nutzungsart = null;

    protected $vermarktungsart = null;

    protected $objektart = null;

    protected $ausstattBeschr = null;

    protected $breitengrad = null;

    protected $laengengrad = null;

    protected $strasse = null;

    protected $hausnummer = null;

    protected $bundesland = null;

    protected $land = null;

    protected $flur = null;

    protected $flurstueck  = null;

    protected $gemarkung = null;

    protected $anzahlEtagen = null;

    protected $regionalerZusatz = null;

    /**
     * @param null $nutzungsart
     *
     * @return $this
     */
    public function setNutzungsart($nutzungsart)
    {
        $this->nutzungsart = $nutzungsart;

        return $this;
    }

    /**
     * @return null
     */
    public function getNutzungsart()
    {
        return $this->nutzungsart;
    }

    /**
     * @param null $objektart
     *
     * @return $this
     */
    public function setObjektart($objektart)
    {
        $this->objektart = $objektart;

        return $this;
    }

    /**
     * @return null
     */
    public function getObjektart()
    {
        return $this->objektart;
    }

    /**
     * @param null $vermarktungsart
     *
     * @return $this
     */
    public function setVermarktungsart($vermarktungsart)
    {
        $this->vermarktungsart = $vermarktungsart;

        return $this;
    }

    /**
     * @return null
     */
    public function getVermarktungsart()
    {
        return $this->vermarktungsart;
    }

    /**
     * @param null $dreizeiler
     *
     * @return $this
     */
    public function setDreizeiler($dreizeiler)
    {
        $this->dreizeiler = $dreizeiler;

        return $this;
    }

    /**
     * @return null
     */
    public function getDreizeiler()
    {
        return $this->dreizeiler;
    }

    /**
     * @param null $etage
     *
     * @return $this
     */
    public function setEtage($etage)
    {
        $this->etage = $etage;

        return $this;
    }

    /**
     * @return null
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * @param null $gesamtmiete
     *
     * @return $this
     */
    public function setGesamtmiete($gesamtmiete)
    {
        $this->gesamtmiete = $gesamtmiete;

        return $this;
    }

    /**
     * @return null
     */
    public function getGesamtmiete()
    {
        return $this->gesamtmiete;
    }

    /**
     * @param null $grundflaeche
     *
     * @return $this
     */
    public function setGrundflaeche($grundflaeche)
    {
        $this->grundflaeche = $grundflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getGrundflaeche()
    {
        return $this->grundflaeche;
    }

    /**
     * @param mixed $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $kaufpreis
     *
     * @return $this
     */
    public function setKaufpreis($kaufpreis)
    {
        $this->kaufpreis = $kaufpreis;

        return $this;
    }

    /**
     * @return null
     */
    public function getKaufpreis()
    {
        return $this->kaufpreis;
    }

    /**
     * @param null $naehe
     *
     * @return $this
     */
    public function setNaehe($naehe)
    {
        $this->naehe = $naehe;

        return $this;
    }

    /**
     * @return null
     */
    public function getNaehe()
    {
        return $this->naehe;
    }

    /**
     * @param null $nutzflaeche
     *
     * @return $this
     */
    public function setNutzflaeche($nutzflaeche)
    {
        $this->nutzflaeche = $nutzflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getNutzflaeche()
    {
        return $this->nutzflaeche;
    }

    /**
     * @param null $objektbeschreibung
     *
     * @return $this
     */
    public function setObjektbeschreibung($objektbeschreibung)
    {
        $this->objektbeschreibung = $objektbeschreibung;

        return $this;
    }

    /**
     * @return null
     */
    public function getObjektbeschreibung()
    {
        return $this->objektbeschreibung;
    }

    /**
     * @param mixed $objektnummer
     *
     * @return $this
     */
    public function setObjektnummer($objektnummer)
    {
        $this->objektnummer = $objektnummer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjektnummer()
    {
        return $this->objektnummer;
    }

    /**
     * @param null $ort
     *
     * @return $this
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;

        return $this;
    }

    /**
     * @return null
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * @param null $plz
     *
     * @return $this
     */
    public function setPlz($plz)
    {
        $this->plz = $plz;

        return $this;
    }

    /**
     * @return null
     */
    public function getPlz()
    {
        return $this->plz;
    }

    /**
     * @param null $projectId
     *
     * @return $this
     */
    public function setProjektId($projectId)
    {
        $this->projektId = $projectId;

        return $this;
    }

    /**
     * @return null
     */
    public function getProjektId()
    {
        return $this->projektId;
    }

    /**
     * @param null $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null $titel
     *
     * @return $this
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;

        return $this;
    }

    /**
     * @return null
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * @param null $tuernummer
     *
     * @return $this
     */
    public function setTuernummer($tuernummer)
    {
        $this->tuernummer = $tuernummer;

        return $this;
    }

    /**
     * @return null
     */
    public function getTuernummer()
    {
        return $this->tuernummer;
    }

    /**
     * @param null $ausstattBeschr
     *
     * @return $this
     */
    public function setAusstattBeschr($ausstattBeschr)
    {
        $this->ausstattBeschr = $ausstattBeschr;

        return $this;
    }

    /**
     * @return null
     */
    public function getAusstattBeschr()
    {
        return $this->ausstattBeschr;
    }

    /**
     * @param null $breitengrad
     *
     * @return $this
     */
    public function setBreitengrad($breitengrad)
    {
        $this->breitengrad = $breitengrad;

        return $this;
    }

    /**
     * @return null
     */
    public function getBreitengrad()
    {
        return $this->breitengrad;
    }

    /**
     * @param null $laengengrad
     *
     * @return $this
     */
    public function setLaengengrad($laengengrad)
    {
        $this->laengengrad = $laengengrad;

        return $this;
    }

    /**
     * @return null
     */
    public function getLaengengrad()
    {
        return $this->laengengrad;
    }

    /**
     * @param null $anzahlEtagen
     *
     * @return $this
     */
    public function setAnzahlEtagen($anzahlEtagen)
    {
        $this->anzahlEtagen = $anzahlEtagen;

        return $this;
    }

    /**
     * @return null
     */
    public function getAnzahlEtagen()
    {
        return $this->anzahlEtagen;
    }

    /**
     * @param null $bundesland
     *
     * @return $this
     */
    public function setBundesland($bundesland)
    {
        $this->bundesland = $bundesland;

        return $this;
    }

    /**
     * @return null
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }

    /**
     * @param null $flur
     *
     * @return $this
     */
    public function setFlur($flur)
    {
        $this->flur = $flur;

        return $this;
    }

    /**
     * @return null
     */
    public function getFlur()
    {
        return $this->flur;
    }

    /**
     * @param null $flurstueck
     *
     * @return $this
     */
    public function setFlurstueck($flurstueck)
    {
        $this->flurstueck = $flurstueck;

        return $this;
    }

    /**
     * @return null
     */
    public function getFlurstueck()
    {
        return $this->flurstueck;
    }

    /**
     * @param null $gemarkung
     *
     * @return $this
     */
    public function setGemarkung($gemarkung)
    {
        $this->gemarkung = $gemarkung;

        return $this;
    }

    /**
     * @return null
     */
    public function getGemarkung()
    {
        return $this->gemarkung;
    }

    /**
     * @param null $hausnummer
     *
     * @return $this
     */
    public function setHausnummer($hausnummer)
    {
        $this->hausnummer = $hausnummer;

        return $this;
    }

    /**
     * @return null
     */
    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    /**
     * @param null $land
     *
     * @return $this
     */
    public function setLand($land)
    {
        $this->land = $land;

        return $this;
    }

    /**
     * @return null
     */
    public function getLand()
    {
        return $this->land;
    }

    /**
     * @param null $regionalerZusatz
     *
     * @return $this
     */
    public function setRegionalerZusatz($regionalerZusatz)
    {
        $this->regionalerZusatz = $regionalerZusatz;

        return $this;
    }

    /**
     * @return null
     */
    public function getRegionalerZusatz()
    {
        return $this->regionalerZusatz;
    }

    /**
     * @param null $strasse
     *
     * @return $this
     */
    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;

        return $this;
    }

    /**
     * @return null
     */
    public function getStrasse()
    {
        return $this->strasse;
    }



}
