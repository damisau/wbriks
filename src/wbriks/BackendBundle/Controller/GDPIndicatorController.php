<?php

namespace wbriks\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use wbriks\BackendBundle\Entity\Country;
use wbriks\BackendBundle\Entity\GDPIndicatorValue;

class GDPIndicatorController extends Controller
{
    /**
     * @param $startYear
     * @param $endYear
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renewAction($startYear, $endYear){
    	$url = "http://api.worldbank.org/countries/all/indicators/NY.GDP.MKTP.CD?date=".$startYear.":".$endYear."&page=1&per_page=5000&format=json";
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$json_response = curl_exec($ch);
		$data = json_decode($json_response, true);
		$em = $this->getDoctrine()->getManager();
		
		foreach($data[1] as $record){
			$gdpDAO = new GDPIndicatorValue();
			$gdpDAO->setCountryId($record['country']['id']);
			$gdpDAO->setYear($record['date']);
			$gdpDAO->setValue($record['value']);
			$em->persist($gdpDAO);
			$em->flush();
		}
		return $this->render('wbriksBackendBundle:countries:countriesList.html.twig', array('data' => 'naaarf'));
	}


    /**
     * Lists all gdp values by country
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){

        $total  = $this->getDoctrine()->getRepository('wbriksBackendBundle:GDPIndicatorValue')
                    ->createQueryBuilder('v')
                    ->getQuery()
                    ->getResult();

        /* result for total */
        $total_articles    = count($total);

        $entities          = $this->getDoctrine()->getRepository('wbriksBackendBundle:GDPIndicatorValue')
                                ->createQueryBuilder('v')
                                ->select('v.id, v.value','v.year', 'c.name')
                                ->from('wbriksBackendBundle:Country', 'c')
                                ->where('v.country_id = c.iso_2_alpha_code')
                                ->orderBy('c.name','ASC')
                                ->orderBy('v.year','ASC')
                                ->getQuery()
                                ->getResult();

        return $this->render('wbriksBackendBundle:indicators\gdp:list.html.twig', array(
            'entities' => $entities,
            'total_articles' => $total_articles,
        ));
    }

    /**
     * Render a map of the data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mapAction(){
        $entities = $this->getDoctrine()->getRepository('wbriksBackendBundle:GDPIndicatorValue')
            ->createQueryBuilder('v')
            ->select('v.id, v.value','v.year', 'c.name')
            ->from('wbriksBackendBundle:Country', 'c')
            ->where('v.country_id = c.iso_2_alpha_code')
            ->andWhere('v.year = 1970')
            ->orderBy('c.name','ASC')
            ->orderBy('v.year','ASC')
            ->getQuery()
            ->getResult();
        return $this->render('wbriksBackendBundle:indicators\gdp:map.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Return json data of a specific year
     * @param int $year
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getJSONOfYearAction($year = 1970){
        $request = $this->getRequest();
        $format = $request->getRequestFormat();
        $entities = $this->getDoctrine()->getRepository('wbriksBackendBundle:GDPIndicatorValue')
            ->createQueryBuilder('v')
            ->select('c.name','v.value')
            ->from('wbriksBackendBundle:Country', 'c')
            ->where('v.country_id = c.iso_2_alpha_code')
            ->andWhere('v.year = ' . $year)
            ->orderBy('c.name','ASC')
            ->orderBy('v.year','ASC')
            ->getQuery()
            ->getResult();
        return $this->render('wbriksBackendBundle:indicators\gdp:json.html.twig', array('entities' => $entities

        ));
    }

    public function showAction($id){

    }
}