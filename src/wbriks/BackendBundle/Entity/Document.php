<?php
namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="document")
 */

class Document
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $title;

    /**
     * @ORM\Column(type="short_desc", nullable=true)
     */
    protected $short_desc;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $long_desc;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $language;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $path;


    /**
     * @ORM\ManyToMany(targetEntity="Arrangement")
     * @ORM\JoinTable(name="document_arrangement",
     *     joinColumns={@ORM\JoinColumn(name="arrangement_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")}
     *     )
     */
    protected $arrangements;

    /**
     * @ORM\ManyToMany(targetEntity="Country")
     * @ORM\JoinTable(name="document_country",
     *     joinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")}
     *     )
     */
    protected $countries;

    /**
     *
     */
    public function __construct() {
        $this->arrangements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param $arrangements
     */
    public function setArrangements($arrangements) {
        $this->arrangements = $arrangements;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getArrangements() {
        return $this->arrangements;
    }

    /**
     * @param $countries
     */
    public function setCountries($countries) {
        $this->countries = $countries;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCountries() {
        return $this->countries;
    }

    /**
     * @param $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param $language
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param $long_desc
     */
    public function setLongDesc($long_desc) {
        $this->long_desc = $long_desc;
    }

    /**
     * @return mixed
     */
    public function getLongDesc() {
        return $this->long_desc;
    }

    /**
     * @param $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param $short_desc
     */
    public function setShortDesc($short_desc) {
        $this->short_desc = $short_desc;
    }

    /**
     * @return mixed
     */
    public function getShortDesc() {
        return $this->short_desc;
    }

    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

}