<?php

namespace Justimmo\Model;

class Realty
{
    /**
     * Orientation constants and their english identifiers.
     * Beware: These are not primarily cardinal directions but orientation of windowed facades (e.g. east-west, north-east-south).
     */
    const ORIENTATION_NORTH            = 1;
    const ORIENTATION_NORTH_EAST       = 2;
    const ORIENTATION_EAST             = 3;
    const ORIENTATION_SOUTH_EAST       = 4;
    const ORIENTATION_SOUTH            = 5;
    const ORIENTATION_SOUTH_WEST       = 6;
    const ORIENTATION_WEST             = 7;
    const ORIENTATION_NORTH_WEST       = 8;
    const ORIENTATION_NORTH_SOUTH      = 9;
    const ORIENTATION_EAST_WEST        = 10;
    const ORIENTATION_NORTH_EAST_WEST  = 11;
    const ORIENTATION_NORTH_EAST_SOUTH = 12;
    const ORIENTATION_SOUTH_EAST_WEST  = 13;
    const ORIENTATION_SOUTH_WEST_NORTH = 14;

    protected $id;

    protected $propertyNumber;

    protected $title;

    protected $teaser;

    protected $proximity;

    protected $description ;

    protected $otherInformation;

    protected $tier;

    protected $doorNumber;

    protected $zipCode;

    protected $place;

    protected $purchasePrice;

    protected $purchasePriceNet;

    protected $purchasePriceVat;

    protected $totalRent;

    protected $purchasePricePerSqm;

    /**
     * @var float
     */
    protected $rentPerSqmFrom;

    /**
     * @var float
     */
    protected $rentPerSqm;

    /**
     * @var float
     */
    protected $operatingCostsPerSqmFrom;

    /**
     * @var float
     */
    protected $operatingCostsPerSqm;

    /**
     * @var float
     */
    protected $floorAreaFrom;

    /**
     * @var float
     */
    protected $floorArea;

    protected $surfaceArea;

    protected $livingArea;

    protected $totalArea;

    /**
     * @var int
     */
    protected $projectId;

    protected $status;

    protected $statusId;

    protected $occupancy;

    protected $marketingType;

    /**
     * @var string
     */
    protected $realtyType;

    /**
     * @var int
     */
    protected $realtyTypeId;

    /**
     * @var string
     */
    protected $realtyTypeName;

    /**
     * @var string
     */
    protected $subRealtyType;

    /**
     * @var int
     */
    protected $subRealtyTypeId;

    /**
     * @var string
     */
    protected $subRealtyTypeName;

    protected $equipmentDescription;

    protected $latitude;

    protected $longitude;

    /**
     * @var double
     */
    protected $latitudePrecise;

    /**
     * @var double
     */
    protected $longitudePrecise;

    /**
     * @var int
     */
    protected $orientation;

    protected $street;

    protected $houseNumber;

    protected $federalState;

    protected $country;

    protected $hallway;

    protected $landParcel;

    protected $district;

    protected $tierCount;

    protected $regionalAddition;

    protected $netRent;

    protected $additionalCharges;

    protected $heatingCosts;

    protected $currency;

    protected $surety;

    protected $surety_text;

    protected $compensation;

    protected $buildingSubsidies;

    protected $yield;

    protected $netEarningMonthly;

    protected $netEarningYearly;

    protected $totalRentVat;

    protected $additionalCosts = array();

    protected $transferTax;

    protected $landRegistration;

    protected $attachments = array();

    protected $yearBuilt;

    protected $age;

    protected $infrastructureProvision;

    protected $condition;

    protected $equipment = array();

    protected $roomCount;

    protected $bathroomCount;

    protected $toiletRoomCount;

    protected $balconyTerraceCount;

    protected $balconyTerraceArea;

    protected $balconyCount;

    protected $terraceCount;

    protected $gardenArea;

    protected $cellarArea;

    protected $officeArea;

    protected $storageArea;

    protected $loggiaCount;

    protected $loggiaArea;

    protected $balconyArea;

    protected $terraceArea;

    protected $garageCount;

    protected $garageArea;

    protected $parkingCount;

    protected $parkingArea;

    protected $storeRoomCount;

    protected $contractEstablishmentCosts;

    protected $commission;

    protected $locality;

    protected $categories = array();

    protected $availableFrom;

    protected $rentDuration;

    protected $rentDurationType;

    /**
     * @var float
     */
    protected $buildableArea;

    /**
     * @var EnergyPass
     */
    protected $energyPass;

    /**
     * @var Employee
     */
    protected $contact;

    /**
     * @var string
     */
    protected $freetext1 = null;
    /**
     * @var string
     */
    protected $freetext2 = null;
    /**
     * @var string
     */
    protected $freetext3 = null;

    /**
     * @var string
     */
    protected $costsExplanation;

    /**
     * @var int
     */
    protected $styleOfBuildingId;

    /**
     * @var \DateTime
     */
    protected $procuredAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $stair;

    /**
     * @var string
     */
    protected $realtySystemType;

    /**
     * @var int
     */
    protected $parentId;

    /**
     * @var bool
     */
    protected $showInSearch;

    /**
     * The rent net value
     *
     * @var float
     */
    protected $rentNet;

    /**
     * The rent vat in percent
     *
     * @var float
     */
    protected $rentVat;

    /**
     * The rent vat type [numeric | percent]
     *
     * @var string
     */
    protected $rentVatType;

    /**
     * The rent gross value
     *
     * @var float
     */
    protected $rentGross;

    /**
     * The calculated vat value in currency
     *
     * @var double
     */
    protected $rentVatValue;

    /**
     * The rent vat value as inputed by user
     *
     * @var double
     */
    protected $rentVatInput;

    /**
     * @var bool
     */
    protected $isReference = false;

    /**
     * @param null $nutzungsart
     *
     * @return $this
     */
    public function setOccupancy($nutzungsart)
    {
        $this->occupancy = $nutzungsart;

        return $this;
    }

    /**
     * @return null
     */
    public function getOccupancy()
    {
        return $this->occupancy;
    }

    /**
     * @param null $objektart
     *
     * @return $this
     */
    public function setRealtyType($objektart)
    {
        $this->realtyType = $objektart;

        return $this;
    }

    /**
     * returns the openimmo conform realty type
     *
     * @return null|string
     */
    public function getRealtyType()
    {
        return $this->realtyType;
    }

    /**
     * returns the justimmo id of the realty type
     *
     * @return int|null
     */
    public function getRealtyTypeId()
    {
        return $this->realtyTypeId;
    }

    /**
     * @param int|null $realtyTypeId
     *
     * @return $this
     */
    public function setRealtyTypeId($realtyTypeId)
    {
        $this->realtyTypeId = $realtyTypeId;

        return $this;
    }

    /**
     * returns the justimmo name of the realty type
     *
     * @return null|string
     */
    public function getRealtyTypeName()
    {
        return $this->realtyTypeName;
    }

    /**
     * @param null|string $realtyTypeName
     *
     * @return $this
     */
    public function setRealtyTypeName($realtyTypeName)
    {
        $this->realtyTypeName = $realtyTypeName;

        return $this;
    }

    /**
     * returns the openimmo conform sub realty type
     *
     * @return null|string
     */
    public function getSubRealtyType()
    {
        return $this->subRealtyType;
    }

    /**
     * @param null|string $subRealtyType
     *
     * @return $this
     */
    public function setSubRealtyType($subRealtyType)
    {
        $this->subRealtyType = $subRealtyType;

        return $this;
    }

    /**
     * returns the justimmo id of the sub realty type
     *
     * @return int|null
     */
    public function getSubRealtyTypeId()
    {
        return $this->subRealtyTypeId;
    }

    /**
     * @param int|null $subRealtyTypeId
     *
     * @return $this
     */
    public function setSubRealtyTypeId($subRealtyTypeId)
    {
        $this->subRealtyTypeId = $subRealtyTypeId;
        return $this;
    }

    /**
     * * returns the justimmo name of the sub realty type
     *
     * @return string|null
     */
    public function getSubRealtyTypeName()
    {
        return $this->subRealtyTypeName;
    }

    /**
     * @param string|null $subRealtyTypeName
     *
     * @return $this
     */
    public function setSubRealtyTypeName($subRealtyTypeName)
    {
        $this->subRealtyTypeName = $subRealtyTypeName;
        return $this;
    }

    /**
     * @param null $vermarktungsart
     *
     * @return $this
     */
    public function setMarketingType($vermarktungsart)
    {
        $this->marketingType = $vermarktungsart;

        return $this;
    }

    /**
     * @return null
     */
    public function getMarketingType()
    {
        return $this->marketingType;
    }

    /**
     * @param null $dreizeiler
     *
     * @return $this
     */
    public function setTeaser($dreizeiler)
    {
        $this->teaser = $dreizeiler;

        return $this;
    }

    /**
     * @return null
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @param null $etage
     *
     * @return $this
     */
    public function setTier($etage)
    {
        $this->tier = $etage;

        return $this;
    }

    /**
     * @return null
     */
    public function getTier()
    {
        return $this->tier;
    }

    /**
     * @param null $gesamtmiete
     *
     * @return $this
     */
    public function setTotalRent($gesamtmiete)
    {
        $this->totalRent = $gesamtmiete;

        return $this;
    }

    /**
     * @return null
     */
    public function getTotalRent()
    {
        return $this->totalRent;
    }

    /**
     * @param null $grundflaeche
     *
     * @return $this
     */
    public function setSurfaceArea($grundflaeche)
    {
        $this->surfaceArea = $grundflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getSurfaceArea()
    {
        return $this->surfaceArea;
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
    public function setPurchasePrice($kaufpreis)
    {
        $this->purchasePrice = $kaufpreis;

        return $this;
    }

    /**
     * @return null
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @param null $kaufpreisnetto
     *
     * @return $this
     */
    public function setPurchasePriceNet($kaufpreisnetto)
    {
        $this->purchasePriceNet = $kaufpreisnetto;

        return $this;
    }

    /**
     * @return null
     */
    public function getPurchasePriceNet()
    {
        return $this->purchasePriceNet;
    }

    /**
     * @param null $kaufpreisust
     *
     * @return $this
     */
    public function setPurchasePriceVat($kaufpreisust)
    {
        $this->purchasePriceVat = $kaufpreisust;

        return $this;
    }

    /**
     * @return null
     */
    public function getPurchasePriceVat()
    {
        return $this->purchasePriceVat;
    }

    /**
     * @param null $naehe
     *
     * @return $this
     */
    public function setProximity($naehe)
    {
        $this->proximity = $naehe;

        return $this;
    }

    /**
     * @return null
     */
    public function getProximity()
    {
        return $this->proximity;
    }

    /**
     * @return null
     */
    public function getFloorAreaFrom()
    {
        return $this->floorAreaFrom;
    }

    /**
     * @param null $floorAreaFrom
     *
     * @return $this
     */
    public function setFloorAreaFrom($floorAreaFrom)
    {
        $this->floorAreaFrom = $floorAreaFrom;

        return $this;
    }

     /**
     * @param null $nutzflaeche
     *
     * @return $this
     */
    public function setFloorArea($nutzflaeche)
    {
        $this->floorArea = $nutzflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getFloorArea()
    {
        return $this->floorArea;
    }

    /**
     * @param null $objektbeschreibung
     *
     * @return $this
     */
    public function setDescription($objektbeschreibung)
    {
        $this->description = $objektbeschreibung;

        return $this;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $sonstige_angaben
     *
     * @return $this
     */
    public function setOtherInformation($sonstige_angaben)
    {
        $this->otherInformation = $sonstige_angaben;

        return $this;
    }

    /**
     * @return string
     */
    public function getOtherInformation()
    {
        return $this->otherInformation;
    }

    /**
     * @param mixed $objektnummer
     *
     * @return $this
     */
    public function setPropertyNumber($objektnummer)
    {
        $this->propertyNumber = $objektnummer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPropertyNumber()
    {
        return $this->propertyNumber;
    }

    /**
     * @param null $ort
     *
     * @return $this
     */
    public function setPlace($ort)
    {
        $this->place = $ort;

        return $this;
    }

    /**
     * @return null
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param null $plz
     *
     * @return $this
     */
    public function setZipCode($plz)
    {
        $this->zipCode = $plz;

        return $this;
    }

    /**
     * @return null
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param null|int $projectId
     *
     * @return $this
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * @return null
     */
    public function getProjectId()
    {
        return $this->projectId;
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
    public function setTitle($titel)
    {
        $this->title = $titel;

        return $this;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null $tuernummer
     *
     * @return $this
     */
    public function setDoorNumber($tuernummer)
    {
        $this->doorNumber = $tuernummer;

        return $this;
    }

    /**
     * @return null
     */
    public function getDoorNumber()
    {
        return $this->doorNumber;
    }

    /**
     * @param null $ausstattBeschr
     *
     * @return $this
     */
    public function setEquipmentDescription($ausstattBeschr)
    {
        $this->equipmentDescription = $ausstattBeschr;

        return $this;
    }

    /**
     * @return null
     */
    public function getEquipmentDescription()
    {
        return $this->equipmentDescription;
    }

    /**
     * @param null $breitengrad
     *
     * @return $this
     */
    public function setLatitude($breitengrad)
    {
        $this->latitude = $breitengrad;

        return $this;
    }

    /**
     * @return null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param null $laengengrad
     *
     * @return $this
     */
    public function setLongitude($laengengrad)
    {
        $this->longitude = $laengengrad;

        return $this;
    }

    /**
     * @return null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param null $anzahlEtagen
     *
     * @return $this
     */
    public function setTierCount($anzahlEtagen)
    {
        $this->tierCount = $anzahlEtagen;

        return $this;
    }

    /**
     * @return null
     */
    public function getTierCount()
    {
        return $this->tierCount;
    }

    /**
     * @param null $bundesland
     *
     * @return $this
     */
    public function setFederalState($bundesland)
    {
        $this->federalState = $bundesland;

        return $this;
    }

    /**
     * @return null
     */
    public function getFederalState()
    {
        return $this->federalState;
    }

    /**
     * @param null $flur
     *
     * @return $this
     */
    public function setHallway($flur)
    {
        $this->hallway = $flur;

        return $this;
    }

    /**
     * @return null
     */
    public function getHallway()
    {
        return $this->hallway;
    }

    /**
     * @param null $flurstueck
     *
     * @return $this
     */
    public function setLandParcel($flurstueck)
    {
        $this->landParcel = $flurstueck;

        return $this;
    }

    /**
     * @return null
     */
    public function getLandParcel()
    {
        return $this->landParcel;
    }

    /**
     * @param null $gemarkung
     *
     * @return $this
     */
    public function setDistrict($gemarkung)
    {
        $this->district = $gemarkung;

        return $this;
    }

    /**
     * @return null
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param null $hausnummer
     *
     * @return $this
     */
    public function setHouseNumber($hausnummer)
    {
        $this->houseNumber = $hausnummer;

        return $this;
    }

    /**
     * @return null
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param null $land
     *
     * @return $this
     */
    public function setCountry($land)
    {
        $this->country = $land;

        return $this;
    }

    /**
     * @return null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param null $regionalerZusatz
     *
     * @return $this
     */
    public function setRegionalAddition($regionalerZusatz)
    {
        //bc compat to old list format
        if ($this->proximity === null) {
            $this->proximity = $regionalerZusatz;
        }

        $this->regionalAddition = $regionalerZusatz;

        return $this;
    }

    /**
     * @return null
     */
    public function getRegionalAddition()
    {
        return $this->regionalAddition;
    }

    /**
     * @param null $strasse
     *
     * @return $this
     */
    public function setStreet($strasse)
    {
        $this->street = $strasse;

        return $this;
    }

    /**
     * @return null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param null $gesamtMieteUst
     *
     * @return $this
     */
    public function setTotalRentVat($gesamtMieteUst)
    {
        $this->totalRentVat = $gesamtMieteUst;

        return $this;
    }

    /**
     * @return null
     */
    public function getTotalRentVat()
    {
        return $this->totalRentVat;
    }

    /**
     * @param null $heizkosten
     *
     * @return $this
     */
    public function setHeatingCosts($heizkosten)
    {
        $this->heatingCosts = $heizkosten;

        return $this;
    }

    /**
     * @return null
     */
    public function getHeatingCosts()
    {
        return $this->heatingCosts;
    }

    /**
     * @param null $kaution
     *
     * @return $this
     */
    public function setSurety($kaution)
    {
        $this->surety = $kaution;

        return $this;
    }

    /**
     * @return null
     */
    public function getSurety()
    {
        return $this->surety;
    }

    /**
     * @param null $kautionText
     *
     * @return $this
     */
    public function setSuretyText($kautionText)
    {
        $this->surety_text = $kautionText;

        return $this;
    }

    /**
     * @return null
     */
    public function getSuretyText()
    {
        return $this->surety_text;
    }

    /**
     * @param null $abstand
     *
     * @return $this
     */
    public function setCompensation($abstand)
    {
        $this->compensation = $abstand;

        return $this;
    }

    /**
     * @return null
     */
    public function getCompensation()
    {
        return $this->compensation;
    }

    /**
     * @param null $nebenkosten
     *
     * @return $this
     */
    public function setAdditionalCharges($nebenkosten)
    {
        $this->additionalCharges = $nebenkosten;

        return $this;
    }

    /**
     * @return null
     */
    public function getAdditionalCharges()
    {
        return $this->additionalCharges;
    }

    /**
     * @param null $nettoKaltMiete
     *
     * @return $this
     */
    public function setNetRent($nettoKaltMiete)
    {
        $this->netRent = $nettoKaltMiete;

        return $this;
    }

    /**
     * This represents the net sum of all fields in the rent group: <nettokaltmiete>482.95</nettokaltmiete>
     *
     * @see http://api-docs.justimmo.at/preise/index.html
     *
     * If you want to value of the rent field only please us getRentNet()
     *
     * @return null
     */
    public function getNetRent()
    {
        return $this->netRent;
    }

    /**
     * @param null $nettoertragJaehrlich
     *
     * @return $this
     */
    public function setNetEarningYearly($nettoertragJaehrlich)
    {
        $this->netEarningYearly = $nettoertragJaehrlich;

        return $this;
    }

    /**
     * @return null
     */
    public function getNetEarningYearly()
    {
        return $this->netEarningYearly;
    }

    /**
     * @param null $nettoertragMonatlich
     *
     * @return $this
     */
    public function setNetEarningMonthly($nettoertragMonatlich)
    {
        $this->netEarningMonthly = $nettoertragMonatlich;

        return $this;
    }

    /**
     * @return null
     */
    public function getNetEarningMonthly()
    {
        return $this->netEarningMonthly;
    }

    /**
     * @param null $rendite
     *
     * @return $this
     */
    public function setYield($rendite)
    {
        $this->yield = $rendite;

        return $this;
    }

    /**
     * @return null
     */
    public function getYield()
    {
        return $this->yield;
    }

    /**
     * @param null $waehrung
     *
     * @return $this
     */
    public function setCurrency($waehrung)
    {
        $this->currency = $waehrung;

        return $this;
    }

    /**
     * @return null
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param null $wohnbaufoerderung
     *
     * @return $this
     */
    public function setBuildingSubsidies($wohnbaufoerderung)
    {
        $this->buildingSubsidies = $wohnbaufoerderung;

        return $this;
    }

    /**
     * @return null
     */
    public function getBuildingSubsidies()
    {
        return $this->buildingSubsidies;
    }

    /**
     * @param array $zusatzkosten
     *
     * @return $this
     */
    public function setAdditionalCosts(array $zusatzkosten)
    {
        $this->additionalCosts = $zusatzkosten;

        return $this;
    }

    /**
     * @param string          $key
     * @param AdditionalCosts $zusatzkosten
     *
     * @return $this
     */
    public function addAdditionalCosts($key, AdditionalCosts $zusatzkosten)
    {
        $this->additionalCosts[$key] = $zusatzkosten;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalCosts()
    {
        return $this->additionalCosts;
    }

    /**
     * @return double|null
     */
    public function getOperatingCostsGross()
    {
        return array_key_exists('betriebskosten', $this->additionalCosts) ? $this->additionalCosts['betriebskosten']->getGross() : null;
    }

    /**
     * @return double|null
     */
    public function getOperatingCostsNet()
    {
        return array_key_exists('betriebskosten', $this->additionalCosts) ? $this->additionalCosts['betriebskosten']->getNet() : null;
    }

    /**
     * @return double|null
     */
    public function getOperatingCostsVat()
    {
        return array_key_exists('betriebskosten', $this->additionalCosts) ? $this->additionalCosts['betriebskosten']->getVat() : null;
    }

    /**
     * @return double|null
     */
    public function getHeatingCostsGross()
    {
        return array_key_exists('heizkosten', $this->additionalCosts) ? $this->additionalCosts['heizkosten']->getGross() : null;
    }

    /**
     * @return double|null
     */
    public function getHeatingCostsNet()
    {
        return array_key_exists('heizkosten', $this->additionalCosts) ? $this->additionalCosts['heizkosten']->getNet() : null;
    }

    /**
     * @return double|null
     */
    public function getHeatingCostsVat()
    {
        return array_key_exists('heizkosten', $this->additionalCosts) ? $this->additionalCosts['heizkosten']->getVat() : null;
    }

    /**
     * @param null $grundbucheintragung
     *
     * @return $this
     */
    public function setLandRegistration($grundbucheintragung)
    {
        $this->landRegistration = $grundbucheintragung;

        return $this;
    }

    /**
     * @return null
     */
    public function getLandRegistration()
    {
        return $this->landRegistration;
    }

    /**
     * @param null $grunderwerbssteuer
     *
     * @return $this
     */
    public function setTransferTax($grunderwerbssteuer)
    {
        $this->transferTax = $grunderwerbssteuer;

        return $this;
    }

    /**
     * @return null
     */
    public function getTransferTax()
    {
        return $this->transferTax;
    }

    /**
     * @param array $attachments
     *
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param Attachment $attachment
     *
     * @return $this
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param      $type
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getAttachmentsByType($type, $group = false)
    {
        $attachments = array();

        /** @var \Justimmo\Model\Attachment $attachment */
        foreach ($this->attachments as $attachment) {
            if ($attachment->getType() == $type && ($group === false || $group == $attachment->getGroup())) {
                $attachments[] =  $attachment;
            }
        }

        return $attachments;
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getPictures($group = false)
    {
        return $this->getAttachmentsByType('picture', $group);
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getVideos($group = false)
    {
        return $this->getAttachmentsByType('video', $group);
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getDocuments($group = false)
    {
        return $this->getAttachmentsByType('document', $group);
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getLinks($group = false)
    {
        return $this->getAttachmentsByType('link', $group);
    }

    /**
     * @param null $gesamtflaeche
     *
     * @return $this
     */
    public function setTotalArea($gesamtflaeche)
    {
        $this->totalArea = $gesamtflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getTotalArea()
    {
        return $this->totalArea;
    }

    /**
     * @param null $wohnflaeche
     *
     * @return $this
     */
    public function setLivingArea($wohnflaeche)
    {
        $this->livingArea = $wohnflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getLivingArea()
    {
        return $this->livingArea;
    }

    /**
     * @param null $zustand
     *
     * @return $this
     */
    public function setCondition($zustand)
    {
        $this->condition = $zustand;

        return $this;
    }

    /**
     * @return null
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param null $erschließung
     *
     * @return $this
     */
    public function setInfrastructureProvision($erschließung)
    {
        $this->infrastructureProvision = $erschließung;

        return $this;
    }

    /**
     * @return null
     */
    public function getInfrastructureProvision()
    {
        return $this->infrastructureProvision;
    }

    /**
     * @param null $baujahr
     *
     * @return $this
     */
    public function setYearBuilt($baujahr)
    {
        $this->yearBuilt = $baujahr;

        return $this;
    }

    /**
     * @return null
     */
    public function getYearBuilt()
    {
        return $this->yearBuilt;
    }

    /**
     * @param null $alter
     *
     * @return $this
     */
    public function setAge($alter)
    {
        $this->age = $alter;

        return $this;
    }

    /**
     * @return null
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param \Justimmo\Model\EnergyPass $v
     *
     * @return $this
     */
    public function setEnergyPass(EnergyPass $v)
    {
        $this->energyPass = $v;

        return $this;
    }

    /**
     * @return \Justimmo\Model\EnergyPass
     */
    public function getEnergyPass()
    {
        return $this->energyPass;
    }

    /**
     * @param array $ausstattung
     *
     * @return $this
     */
    public function setEquipment($ausstattung)
    {
        $this->equipment = $ausstattung;

        return $this;
    }

    /**
     * @return array
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addEquipment($key, $value)
    {
        $this->equipment[$key] = $value;

        return $this;
    }

    /**
     * @param null $anzahlAbstellraum
     *
     * @return $this
     */
    public function setStoreRoomCount($anzahlAbstellraum)
    {
        $this->storeRoomCount = $anzahlAbstellraum;

        return $this;
    }

    /**
     * @return null
     */
    public function getStoreRoomCount()
    {
        return $this->storeRoomCount;
    }

    /**
     * @param null $anzahlBadezimmer
     *
     * @return $this
     */
    public function setBathroomCount($anzahlBadezimmer)
    {
        $this->bathroomCount = $anzahlBadezimmer;

        return $this;
    }

    /**
     * @return null
     */
    public function getBathroomCount()
    {
        return $this->bathroomCount;
    }

    /**
     * @param null $anzahlBalkonTerrassen
     *
     * @return $this
     */
    public function setBalconyTerraceCount($anzahlBalkonTerrassen)
    {
        $this->balconyTerraceCount = $anzahlBalkonTerrassen;

        return $this;
    }

    /**
     * @return null
     */
    public function getBalconyTerraceCount()
    {
        return $this->balconyTerraceCount;
    }

    /**
     * @param null $anzahlBalkone
     *
     * @return $this
     */
    public function setBalconyCount($anzahlBalkone)
    {
        $this->balconyCount = $anzahlBalkone;

        return $this;
    }

    /**
     * @return null
     */
    public function getBalconyCount()
    {
        return $this->balconyCount;
    }

    /**
     * @param null $anzahlGaragen
     *
     * @return $this
     */
    public function setGarageCount($anzahlGaragen)
    {
        $this->garageCount = $anzahlGaragen;

        return $this;
    }

    /**
     * @return null
     */
    public function getGarageCount()
    {
        return $this->garageCount;
    }

    /**
     * @param null $garagenFlaeche
     *
     * @return $this
     */
    public function setGarageArea($garagenFlaeche)
    {
        $this->garageArea = $garagenFlaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getGarageArea()
    {
        return $this->garageArea;
    }

    /**
     * @param null $anzahlStellplaetze
     *
     * @return $this
     */
    public function setParkingCount($anzahlStellplaetze)
    {
        $this->parkingCount = $anzahlStellplaetze;

        return $this;
    }

    /**
     * @return null
     */
    public function getParkingCount()
    {
        return $this->parkingCount;
    }

    /**
     * @param null $stellplatzFlaeche
     *
     * @return $this
     */
    public function setParkingArea($stellplatzFlaeche)
    {
        $this->parkingArea = $stellplatzFlaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getParkingArea()
    {
        return $this->parkingArea;
    }

    /**
     * @param null $anzahlLoggias
     *
     * @return $this
     */
    public function setLoggiaCount($anzahlLoggias)
    {
        $this->loggiaCount = $anzahlLoggias;

        return $this;
    }

    /**
     * @return null
     */
    public function getLoggiaCount()
    {
        return $this->loggiaCount;
    }

    /**
     * @param null $anzahlSepWc
     *
     * @return $this
     */
    public function setToiletRoomCount($anzahlSepWc)
    {
        $this->toiletRoomCount = $anzahlSepWc;

        return $this;
    }

    /**
     * @return null
     */
    public function getToiletRoomCount()
    {
        return $this->toiletRoomCount;
    }

    /**
     * @param null $anzahlTerrassen
     *
     * @return $this
     */
    public function setTerraceCount($anzahlTerrassen)
    {
        $this->terraceCount = $anzahlTerrassen;

        return $this;
    }

    /**
     * @return null
     */
    public function getTerraceCount()
    {
        return $this->terraceCount;
    }

    /**
     * @param null $anzahlZimmer
     *
     * @return $this
     */
    public function setRoomCount($anzahlZimmer)
    {
        $this->roomCount = $anzahlZimmer;

        return $this;
    }

    /**
     * @return null
     */
    public function getRoomCount()
    {
        return $this->roomCount;
    }

    /**
     * @param null $balkonTerrassenFlaeche
     *
     * @return $this
     */
    public function setBalconyTerraceArea($balkonTerrassenFlaeche)
    {
        $this->balconyTerraceArea = $balkonTerrassenFlaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getBalconyTerraceArea()
    {
        return $this->balconyTerraceArea;
    }

    /**
     * @param null $balkonsFlaeche
     *
     * @return $this
     */
    public function setBalconyArea($balkonsFlaeche)
    {
        $this->balconyArea = $balkonsFlaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getBalconyArea()
    {
        return $this->balconyArea;
    }

    /**
     * @param null $bueroflaeche
     *
     * @return $this
     */
    public function setOfficeArea($bueroflaeche)
    {
        $this->officeArea = $bueroflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getOfficeArea()
    {
        return $this->officeArea;
    }

    /**
     * @param null $gartenflaeche
     *
     * @return $this
     */
    public function setGardenArea($gartenflaeche)
    {
        $this->gardenArea = $gartenflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getGardenArea()
    {
        return $this->gardenArea;
    }

    /**
     * @param null $kellerflaeche
     *
     * @return $this
     */
    public function setCellarArea($kellerflaeche)
    {
        $this->cellarArea = $kellerflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getCellarArea()
    {
        return $this->cellarArea;
    }

    /**
     * @param null $lagerflaeche
     *
     * @return $this
     */
    public function setStorageArea($lagerflaeche)
    {
        $this->storageArea = $lagerflaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getStorageArea()
    {
        return $this->storageArea;
    }

    /**
     * @param null $loggiasFlaeche
     *
     * @return $this
     */
    public function setLoggiaArea($loggiasFlaeche)
    {
        $this->loggiaArea = $loggiasFlaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getLoggiaArea()
    {
        return $this->loggiaArea;
    }

    /**
     * @param null $terrassenFlaeche
     *
     * @return $this
     */
    public function setTerraceArea($terrassenFlaeche)
    {
        $this->terraceArea = $terrassenFlaeche;

        return $this;
    }

    /**
     * @return null
     */
    public function getTerraceArea()
    {
        return $this->terraceArea;
    }

    /**
     * @param \Justimmo\Model\Employee $value
     *
     * @return $this
     */
    public function setContact($value)
    {
        $this->contact = $value;

        return $this;
    }

    /**
     * @return \Justimmo\Model\Employee
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setContractEstablishmentCosts($value)
    {
        $this->contractEstablishmentCosts = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getContractEstablishmentCosts()
    {
        return $this->contractEstablishmentCosts;
    }

    /**
     * @param null|string $freetext1
     */
    public function setFreetext1($freetext1)
    {
        $this->freetext1 = $freetext1;
    }

    /**
     * @return null|string
     */
    public function getFreetext1()
    {
        return $this->freetext1;
    }

    /**
     * @param null|string $freetext2
     */
    public function setFreetext2($freetext2)
    {
        $this->freetext2 = $freetext2;
    }

    /**
     * @return null|string
     */
    public function getFreetext2()
    {
        return $this->freetext2;
    }

    /**
     * @param null|string $freetext3
     */
    public function setFreetext3($freetext3)
    {
        $this->freetext3 = $freetext3;
    }

    /**
     * @return null|string
     */
    public function getFreetext3()
    {
        return $this->freetext3;
    }

    /**
     * @param null|string $freetext3
     */
    public function setCostsExplanation($costsExplanation)
    {
        $this->costsExplanation = $costsExplanation;
    }

    /**
     * @return null|string
     */
    public function getCostsExplanation()
    {
        return $this->costsExplanation;
    }

    /**
     * @return null
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setCommission($value)
    {
        $this->commission = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setLocality($value)
    {
        $this->locality = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setCategories($value)
    {
        $this->categories = $value;

        return $this;
    }

    /**
     * @param $id
     * @param $name
     *
     * @return $this
     */
    public function addCategory($id, $name)
    {
        $this->categories[$id] = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvailableFrom()
    {
        return $this->availableFrom;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setAvailableFrom($value)
    {
        $this->availableFrom = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @param null $statusId
     *
     * @return $this
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRentDurationType()
    {
        return $this->rentDurationType;
    }

    /**
     * @param null|string $rentDurationType
     *
     * @return $this
     */
    public function setRentDurationType($rentDurationType)
    {
        $this->rentDurationType = $rentDurationType;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getRentDuration()
    {
        return $this->rentDuration;
    }

    /**
     * @param null|int $rentDuration
     *
     * @return $this
     */
    public function setRentDuration($rentDuration)
    {
        $this->rentDuration = $rentDuration;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getBuildableArea()
    {
        return $this->buildableArea;
    }

    /**
     * @param float|null $buildableArea
     *
     * @return $this
     */
    public function setBuildableArea($buildableArea)
    {
        $this->buildableArea = $buildableArea;

        return $this;
    }

    /**
     * style of building and age are the same
     *
     * @return null|string
     */
    public function getStyleOfBuilding()
    {
        return $this->getAge();
    }

    /**
     * @return int|null
     */
    public function getStyleOfBuildingId()
    {
        return $this->styleOfBuildingId;
    }

    /**
     * @param int|null $styleOfBuildingId
     *
     * @return $this
     */
    public function setStyleOfBuildingId($styleOfBuildingId)
    {
        $this->styleOfBuildingId = $styleOfBuildingId;

        return $this;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getProcuredAt($format = 'Y-m-d')
    {
        if ($this->procuredAt instanceof \DateTime && $format !== null) {
            return $this->procuredAt->format($format);
        }

        return $this->procuredAt;
    }

    /**
     * @param \DateTime|null $procuredAt
     *
     * @return $this
     */
    public function setProcuredAt(\DateTime $procuredAt = null)
    {
        $this->procuredAt = $procuredAt;

        return $this;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getCreatedAt($format = 'Y-m-d H:i:s')
    {
        if ($this->createdAt instanceof \DateTime && $format !== null) {
            return $this->createdAt->format($format);
        }

        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getUpdatedAt($format = 'Y-m-d H:i:s')
    {
        if ($this->updatedAt instanceof \DateTime && $format !== null) {
            return $this->updatedAt->format($format);
        }

        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getStair()
    {
        return $this->stair;
    }

    /**
     * @param string $stair
     *
     * @return $this
     */
    public function setStair($stair)
    {
        $this->stair = $stair;

        return $this;
    }

    /**
     * @return null
     */
    public function getPurchasePricePerSqm()
    {
        return $this->purchasePricePerSqm;
    }

    /**
     * @param null $purchasePricePerSqm
     *
     * @return $this
     */
    public function setPurchasePricePerSqm($purchasePricePerSqm)
    {
        $this->purchasePricePerSqm = $purchasePricePerSqm;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentPerSqmFrom()
    {
        return $this->rentPerSqmFrom;
    }

    /**
     * @param float $rentPerSqmFrom
     *
     * @return $this
     */
    public function setRentPerSqmFrom($rentPerSqmFrom)
    {
        $this->rentPerSqmFrom = $rentPerSqmFrom;

        return $this;
    }

    /**
     * @return null
     */
    public function getRentPerSqm()
    {
        return $this->rentPerSqm;
    }

    /**
     * @param null $rentPerSqm
     *
     * @return $this
     */
    public function setRentPerSqm($rentPerSqm)
    {
        $this->rentPerSqm = $rentPerSqm;

        return $this;
    }

    /**
     * @return float
     */
    public function getOperatingCostsPerSqmFrom()
    {
        return $this->operatingCostsPerSqmFrom;
    }

    /**
     * @param float $operatingCostsPerSqmFrom
     *
     * @return $this
     */
    public function setOperatingCostsPerSqmFrom($operatingCostsPerSqmFrom)
    {
        $this->operatingCostsPerSqmFrom = $operatingCostsPerSqmFrom;

        return $this;
    }

    /**
     * @return null
     */
    public function getOperatingCostsPerSqm()
    {
        return $this->operatingCostsPerSqm;
    }

    /**
     * @param null $operatingCostsPerSqm
     *
     * @return $this
     */
    public function setOperatingCostsPerSqm($operatingCostsPerSqm)
    {
        $this->operatingCostsPerSqm = $operatingCostsPerSqm;

        return $this;
    }

    /**
     * returns the precise longitude of the realty regardless of the  visibility settings of addresses in justimmo
     *
     * @return double
     */
    public function getLongitudePrecise()
    {
        return $this->longitudePrecise;
    }

    /**
     * @param double $longitudePrecise
     *
     * @return $this
     */
    public function setLongitudePrecise($longitudePrecise)
    {
        $this->longitudePrecise = $longitudePrecise;

        return $this;
    }

    /**
     * returns the precise latitude of the realty regardless of the visibility settings of addresses in justimmo
     *
     * @return double
     */
    public function getLatitudePrecise()
    {
        return $this->latitudePrecise;
    }

    /**
     * @param double $latitudePrecise
     *
     * @return $this
     */
    public function setLatitudePrecise($latitudePrecise)
    {
        $this->latitudePrecise = $latitudePrecise;

        return $this;
    }


    /**
     * Returns the orientation of the realty.
     *
     * @return int
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * @param int $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * @return string
     */
    public function getRealtySystemType()
    {
        return $this->realtySystemType;
    }

    /**
     * @param string $realtySystemType
     *
     * @return $this
     */
    public function setRealtySystemType($realtySystemType)
    {
        $this->realtySystemType = $realtySystemType;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     *
     * @return $this
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowInSearch()
    {
        return $this->showInSearch;
    }

    /**
     * @param bool $showInSearch
     *
     * @return Realty
     */
    public function setShowInSearch($showInSearch)
    {
        $this->showInSearch = $showInSearch;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentNet()
    {
        return $this->rentNet;
    }

    /**
     * @param float $rentNet
     *
     * @return Realty
     */
    public function setRentNet($rentNet)
    {
        $this->rentNet = $rentNet;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentVat()
    {
        return $this->rentVat;
    }

    /**
     * @param float $rentVat
     *
     * @return Realty
     */
    public function setRentVat($rentVat)
    {
        $this->rentVat = $rentVat;

        return $this;
    }

    /**
     * @return string
     */
    public function getRentVatType()
    {
        return $this->rentVatType;
    }

    /**
     * @param string $rentVatType
     *
     * @return Realty
     */
    public function setRentVatType($rentVatType)
    {
        $this->rentVatType = $rentVatType;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentGross()
    {
        return $this->rentGross;
    }

    /**
     * @param float $rentGross
     *
     * @return Realty
     */
    public function setRentGross($rentGross)
    {
        $this->rentGross = $rentGross;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentVatValue()
    {
        return $this->rentVatValue;
    }

    /**
     * @param float $rentVatValue
     *
     * @return Realty
     */
    public function setRentVatValue($rentVatValue)
    {
        $this->rentVatValue = $rentVatValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentVatInput()
    {
        return $this->rentVatInput;
    }

    /**
     * @param float $rentVatInput
     *
     * @return Realty
     */
    public function setRentVatInput($rentVatInput)
    {
        $this->rentVatInput = $rentVatInput;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsReference()
    {
        return $this->isReference;
    }

    /**
     * @param bool $isReference
     *
     * @return Realty
     */
    public function setIsReference($isReference)
    {
        $this->isReference = $isReference;

        return $this;
    }
}
