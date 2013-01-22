<?php
namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use wbriks\BackendBundle\Entity\Country;

/**
 * @ORM\Entity
 * @ORM\Table(name="arrangement")
 * @ORM\Entity(repositoryClass="wbriks\BackendBundle\Entity\ArrangementRepository")
 */

class Arrangement
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=3, nullable=true)
	 */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Country")
     * @ORM\JoinTable(name="arrangements_countries",
     *     joinColumns={@ORM\JoinColumn(name="arrangement_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="iso_2_alpha_code")}
     *     )
     */
    protected $countries;

    /**
     * Constructor
     */
    public function __construct(){
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * get id
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    /**
     * get name
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * set name
     * @param string $name
     * @return Arrangement;
     */
    public function setName($name){
        $this->name = $name;

        return $this;
    }

    /**
     * get countries
     * @return ArrayCollection
     */
    public function getCountries(){
        return $this->countries;
    }
}