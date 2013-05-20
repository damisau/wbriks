<?php
namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="indicator_gdp")
 */

class GDPIndicator
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
     *     inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")}
     *     )
     */
    protected $reporter;

    /**
     * Constructor
     */
    public function __construct(){
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }
}