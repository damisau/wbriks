<?php

namespace wbriks\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use wbriks\BackendBundle\Entity\Country;
use wbriks\BackendBundle\Entity\PopulationValue;

class PopulationController extends Controller
{
    /**
     * @param $startYear
     * @param $endYear
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renewAction($startYear, $endYear){
        $url = "http://api.worldbank.org/countries/all/indicators/SP.POP.TOTL?date=".$startYear.":".$endYear."&page=1&per_page=5000&format=json";
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json_response = curl_exec($ch);
        $data = json_decode($json_response, true);
        $em = $this->getDoctrine()->getManager();

        foreach($data[1] as $record){
            $fdiDAO = new PopulationValue();
            $fdiDAO->setCountryId($record['country']['id']);
            $fdiDAO->setYear($record['date']);
            $fdiDAO->setValue($record['value']);
            $em->persist($fdiDAO);
            $em->flush();
        }
        return $this->render('wbriksBackendBundle:countries:countriesList.html.twig', array('data' => 'naaarf'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){
        $total  = $this->getDoctrine()->getRepository('wbriksBackendBundle:PopulationValue')
            ->createQueryBuilder('v')
            ->getQuery()
            ->getResult();

        /* result for total */
        $total_articles    = count($total);

        $entities          = $this->getDoctrine()->getRepository('wbriksBackendBundle:PopulationValue')
            ->createQueryBuilder('v')
            ->select('v.id, v.value','v.year', 'c.name')
            ->from('wbriksBackendBundle:Country', 'c')
            ->where('v.country_id = c.iso_2_alpha_code')
            ->orderBy('c.name','ASC')
            ->orderBy('v.year','ASC')
            ->getQuery()
            ->getResult();

        return $this->render('wbriksBackendBundle:indicators\population:list.html.twig', array(
            'entities' => $entities,
            'total_articles' => $total_articles,
        ));
    }

    /**
     * List population values by arrangement
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByArrangementAction(){
        $startYear = $this->container->getParameter('population_from_year');
        $endYear = $this->container->getParameter('population_until_year');
        $arrangements = $this->getDoctrine()->getRepository('wbriksBackendBundle:Arrangement')
            ->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
        $indicatorValues = array();
        $conn = $this->container->get('database_connection');

        foreach($arrangements as $arrangement){
            $sql = "SELECT a.id, a.name as arrangement_name, ac.country_id, c.name,pop.value, pop.year, sum(pop.value) as population
                FROM arrangement as a
                JOIN arrangements_countries ac ON (ac.arrangement_id = a.`id`)
                JOIN country as c on (c.iso_2_alpha_code = ac.country_id)
                JOIN indicator_population as pop on (pop.country_id = c.iso_2_alpha_code)
                where a.id = " . $arrangement->getId() . "
                group by pop.year
                order by pop.year desc
                ;";
            $stmt = $conn->query($sql);
            while ($row = $stmt->fetch()) {
                $indicatorValues[$row['arrangement_name']] [$row['year']] = $row['population'];
            }
        }

        return $this->render('wbriksBackendBundle:indicators\population:listByArrangement.html.twig', array(
            'entities' => $arrangements,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'indicatorValues' => $indicatorValues
        ));
    }

    /**
     * Prepare data for map display
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mapAction(){
        $entities = $this->getDoctrine()->getRepository('wbriksBackendBundle:PopulationValue')
            ->createQueryBuilder('v')
            ->select('v.id, v.value','v.year', 'c.name')
            ->from('wbriksBackendBundle:Country', 'c')
            ->where('v.country_id = c.iso_2_alpha_code')
            ->andWhere('v.year = 1970')
            ->orderBy('c.name','ASC')
            ->orderBy('v.year','ASC')
            ->getQuery()
            ->getResult();
        return $this->render('wbriksBackendBundle:indicators\population:map.html.twig', array(
            'entities' => $entities
        ));
    }

    public function getJSONOfYearAction($year = 1970){
        $request = $this->getRequest();
        $format = $request->getRequestFormat();
        $entities = $this->getDoctrine()->getRepository('wbriksBackendBundle:PopulationValue')
            ->createQueryBuilder('v')
            ->select('c.name','v.value')
            ->from('wbriksBackendBundle:Country', 'c')
            ->where('v.country_id = c.iso_2_alpha_code')
            ->andWhere('v.year = ' . $year)
            ->orderBy('c.name','ASC')
            ->orderBy('v.year','ASC')
            ->getQuery()
            ->getResult();
        return $this->render('wbriksBackendBundle:indicators\population:json.html.twig', array('entities' => $entities

        ));
    }

    public function showAction($id){

    }
}