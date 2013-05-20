<?php
namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use wbriks\BackendBundle\Entity\Arrangement;

/**
 * @ORM\Entity
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="wbriks\BackendBundle\Entity\CountryRepository")
 */

class Country
{

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    protected $iso_3_alpha_code;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=2)
     */
    protected $iso_2_alpha_code;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $region_id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $region_name;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $income_level;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $lending_type;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $capital_city;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_user_edited;

    /**
     * @ORM\ManyToMany(targetEntity="Arrangement")
     * @ORM\JoinTable(name="arrangements_countries",
     *     joinColumns={@ORM\JoinColumn(name="county_id", referencedColumnName="iso_2_alpha_code")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="arrangement_id", referencedColumnName="id")}
     *     )
     */
    protected $arrangements;

    public function __construct() {
        $this->arrangements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set iso_3_alpha_code
     *
     * @param string $iso3AlphaCode
     * @return Country
     */
    public function setIso3AlphaCode($iso3AlphaCode) {
        $this->iso_3_alpha_code = $iso3AlphaCode;
        return $this;
    }

    /**
     * Get iso_3_alpha_code
     *
     * @return string
     */
    public function getIso3AlphaCode() {
        return $this->iso_3_alpha_code;
    }

    /**
     * Set iso_2_alpha_code
     *
     * @param string $iso2AlphaCode
     * @return Country
     */
    public function setIso2AlphaCode($iso2AlphaCode) {
        $this->iso_2_alpha_code = $iso2AlphaCode;
        return $this;
    }

    /**
     * Get iso_2_alpha_code
     *
     * @return string
     */
    public function getIso2AlphaCode() {
        return $this->iso_2_alpha_code;
    }

    /**
     * Returns the unique id of this entity, which is the iso_2_alpha_code
     * @return string
     */
    public function getId() {
        return $this->getIso2AlphaCode();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Country
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set region_id
     *
     * @param integer $regionId
     * @return Country
     */
    public function setRegionId($regionId) {
        $this->region_id = $regionId;

        return $this;
    }

    /**
     * Get region_id
     *
     * @return integer
     */
    public function getRegionId() {
        return $this->region_id;
    }

    /**
     * Set region_name
     *
     * @param string $regionName
     * @return Country
     */
    public function setRegionName($regionName) {
        $this->region_name = $regionName;

        return $this;
    }

    /**
     * Get region_name
     *
     * @return string
     */
    public function getRegionName() {
        return $this->region_name;
    }

    /**
     * Set income_level
     *
     * @param string $incomeLevel
     * @return Country
     */
    public function setIncomeLevel($incomeLevel) {
        $this->income_level = $incomeLevel;

        return $this;
    }

    /**
     * Get income_level
     *
     * @return string
     */
    public function getIncomeLevel() {
        return $this->income_level;
    }

    /**
     * Set lending_type
     *
     * @param string $lendingType
     * @return Country
     */
    public function setLendingType($lendingType) {
        $this->lending_type = $lendingType;

        return $this;
    }

    /**
     * Get lending_type
     *
     * @return string
     */
    public function getLendingType() {
        return $this->lending_type;
    }

    /**
     * Set capital_city
     *
     * @param string $capitalCity
     * @return Country
     */
    public function setCapitalCity($capitalCity) {
        $this->capital_city = $capitalCity;

        return $this;
    }

    /**
     * Get capital_city
     *
     * @return string
     */
    public function getCapitalCity() {
        return $this->capital_city;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Country
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Country
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Get is_user_edited
     * @return boolean
     */
    public function getIsUserEdited() {
        return $this->is_user_edited;
    }

    /**
     * Set is_user_edited
     * @param $is_user_edited
     * @return Country
     */
    public function setIsUserEditet($is_user_edited) {
        $this->is_user_edited = $is_user_edited;

        return $this;
    }
}