<?php

namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use wbriks\BackendBundle\Entity\Country;

/**
 * @ORM\Entity
 * @ORM\Table(name="indicator_fdi")
 */
class FDIIndicatorValue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="iso_2_alpha_code")
     * @ORM\Column(type="string", length=2)
     */
    protected $country_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @ORM\Column(type="integer", length=20, nullable=true)
     */
    protected $value;

    public function __construct(){

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return GDPIndicatorValue
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return GDPIndicatorValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set country_id
     *
     * @param string $countryId
     * @return GDPIndicatorValue
     */
    public function setCountryId($countryId = null)
    {
        $this->country_id = $countryId;

        return $this;
    }

    /**
     * Get country_id
     *
     * @return wbriks\BackendBundle\Entity\Country
     */
    public function getCountryId()
    {
        return $this->country_id;
    }
}