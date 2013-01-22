<?php

namespace wbriks\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use wbriks\BackendBundle\Entity\Country;

class CountryController extends Controller
{
    public function createAction(Request $request) {
        $country = new Country();
        $form = $this->createFormBuilder($country)
            ->add('name', 'text')
            ->add('region_name', 'text')
            ->add('capital_city', 'text')
            ->add('iso_2_alpha_code', 'text')
            ->add('longitude', 'text')
            ->add('latitude', 'text')
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($country);
                $em->flush();
                return $this->redirect($this->generateUrl('wbriks_countries_list'));
            }
        }
        return $this->render('wbriksBackendBundle:countries:newCountry.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getEntityManager();
        $country = $em->getRepository('wbriksBackendBundle:Country')->findOneByISO2AlphaCode($id);
        $country = $country[0];

        $form = $this->createFormBuilder($country)
            ->add('name', 'text')
            ->add('id', 'text')
            ->add('region_name', 'text')
            ->add('capital_city', 'text')
            ->add('iso_2_alpha_code', 'text')
            ->add('longitude', 'text')
            ->add('latitude', 'text')
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirect($this->generateUrl('wbriks_countries_list'));
            }
        }
        return $this->render('wbriksBackendBundle:countries:editCountry.html.twig', array('form' => $form->createView()));
    }

    public function updateAction(Request $request) {
        $data = $request->request->all();
        $countryName = $data['form']['id'];
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository('wbriksBackendBundle:Country')->findOneById($countryName);
        $form = $this->createFormBuilder($country)->add('name', 'text')
            ->add('region_name', 'text')
            ->add('id', 'text')
            ->add('capital_city', 'text')
            ->add('iso_2_alpha_code', 'text')
            ->add('longitude', 'text')
            ->add('latitude', 'text')
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $country->setName($data['form']['name']);
                $em->flush();
                return $this->redirect($this->generateUrl('wbriks_countries_list'));
            } else {
                var_dump($form->getErrors());
            }
        }
    }

    public function deleteAction($country_id = 0, Request $request = null) {
        $country = $this->getDoctrine()->getRepository('wbriksBackendBundle:Country')->find($country_id);
        if (!$country) {
            throw $this->createNotFoundException('No country found for id ' . $country_id);
        }
        return $this->render('wbriksBackendBundle:countries:countryDelete.html.twig', array("country" => $country));
    }

    public function doDeleteAction(Request $request) {
        $data = $request->request->all();
        $countryId = $data['country_id'];
        $em = $this->getDoctrine()->getEntityManager();
        $country = $em->getRepository('wbriksBackendBundle:Country')->findOneById($countryId);
        $em->remove($country);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'The country has been deleted!');
        return $this->redirect($this->generateUrl('wbriks_countries_list'));
    }


    public function listCountriesAction() {
        //$repository = $this->getDoctrine()->getRepository('wbriksBackendBundle:Country');
        //$countries = $repository->findAll();
        $em = $this->getDoctrine()->getEntityManager();
        $countries = $em->getRepository('wbriksBackendBundle:Country')->findAllOrderedByName();
        return $this->render('wbriksBackendBundle:countries:countriesList.html.twig', array("countries" => $countries));
    }

    public function updateCountriesAction() {
        $url = "http://api.worldbank.org/country?per_page=5000&region=WLD&format=json";
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json_response = curl_exec($ch);
        $data = json_decode($json_response, true);
        $em = $this->getDoctrine()->getManager();

        foreach ($data[1] as $country) {
            $countryDAO = new Country();
            $countryDAO->setIso2AlphaCode($country['iso2Code']);
            $countryDAO->setName($country['name']);
            $countryDAO->setRegionName($country['region']['value']);
            $countryDAO->setCapitalCity($country['capitalCity']);
            $countryDAO->setLongitude($country['longitude']);
            $countryDAO->setLatitude($country['latitude']);

            $em->persist($countryDAO);
            $em->flush();
        }
        $countries = $em->getRepository('wbriksBackendBundle:Country')->findAllOrderedByName();
        return $this->render('wbriksBackendBundle:countries:countriesList.html.twig', array("countries" => $countries));
    }
}
