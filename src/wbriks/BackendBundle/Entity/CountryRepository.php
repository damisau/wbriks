<?php
namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CountryRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM wbriksBackendBundle:Country p ORDER BY p.name ASC')
            ->getResult();
    }

    public function findOneByName($name){
    	return $this->getEntityManager()
            ->createQuery('SELECT p FROM wbriksBackendBundle:Country p WHERE p.name = \''.$name.'\'')
            ->getResult();
    }


    /**
     * @param $id
     * @return array
     */
    public function findOneByISO2AlphaCode($id){
        return $this->getEntityManager()
                ->createQuery('Select p from wbriksBackendBundle:Country p where p.iso_2_alpha_code = \''.$id.'\'')
                ->getResult();
    }
}