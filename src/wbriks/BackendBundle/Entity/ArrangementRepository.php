<?php
namespace wbriks\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArrangementRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM wbriksBackendBundle:Arrangement a ORDER BY a.name ASC')
            ->getResult();
    }

    public function findOneByName($name){
    	return $this->getEntityManager()
            ->createQuery('SELECT a FROM wbriksBackendBundle:Country a WHERE a.name = \''.$name.'\'')
            ->getResult();
    }
}