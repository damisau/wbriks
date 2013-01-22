<?php

namespace wbriks\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use wbriks\BackendBundle\Entity\Country;
use wbriks\BackendBundle\Entity\Arrangement;

class ArrangementController extends Controller
{
	/**
	 * list all arrangements currently stored in the database
	 */	
	public function listAction(){
		$em = $this->getDoctrine()->getEntityManager();
		$arrangements = $em->getRepository('wbriksBackendBundle:Arrangement')->findAllOrderedByName();
		return $this->render('wbriksBackendBundle:arrangements:arrangementsList.html.twig', array("arrangements" => $arrangements));
	}

	/**
	 * delete an arrangement (after confirm) from the db
	 */
	public function deleteAction(Request $request, $id){
		$arrangement = $this->getDoctrine()->getRepository('wbriksBackendBundle:Arrangement')->find($id);
		if(!$arrangement){
			throw $this->createNotFoundException('No arrangement found for id '.$id);
		}

		if ($request->isMethod('POST')) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($arrangement);
			$em->flush();
			return $this->redirect($this->generateUrl('wbriks_arrangements_list'));
    	}

		return $this->render('wbriksBackendBundle:arrangements:deleteArrangement.html.twig', array("arrangement" => $arrangement));
	}

	public function updateAction(Request $request){
        $data = $request->request->all();
        $countryName = $data['form']['id'];
        $em = $this->getDoctrine()->getEntityManager();
        $arrangement = $em->getRepository('wbriksBackendBundle:Arrangement')->findOneById($countryName);
        $form = $this->createFormBuilder($arrangement)
            ->add('id', 'text')
            ->add('name', 'text')
            ->add('countries', 'entity', array(
            'multiple' => true,
            'expanded' => true,
            'property' => 'name',
            'class'    => 'wbriks\BackendBundle\Entity\Country',
            'attr'     => array('size' => 20),
            'label'    => 'Members of this arrangement:'
        ))
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $arrangement->setName($data['form']['name']);
                $em->flush();
                return $this->redirect($this->generateUrl('wbriks_arrangements_list'));
            } else{
                var_dump($form->getErrors());
            }
        }
	}

	/**
	 * edit an arrangement, store update after submit
	 */
	public function editAction(Request $request,$id = 0){
		$em = $this->getDoctrine()->getEntityManager();
		$country = $em->getRepository('wbriksBackendBundle:Arrangement')->findOneById($id);

		if ($request->isMethod('POST')) {
        	$form->bind($request);
        	if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
    			$em->flush();
				return $this->redirect($this->generateUrl('wbriks_arrangements_list'));
			}
    	}
		$form = $this->createFormBuilder($country)->add('name', 'text')
			->add('id', 'text')
			->add('name', 'text')
			->add('countries', 'entity', array(
							'multiple' => true,
							'expanded' => true,
							'property' => 'name',
							'class'    => 'wbriks\BackendBundle\Entity\Country',
                            'query_builder' => function(\wbriks\BackendBundle\Entity\CountryRepository $er) {
                                return $er->createQueryBuilder('u')
                                    ->orderBy('u.name', 'ASC');
                            },
							'attr'     => array('size' => 20),
							'label'    => 'Members of this arrangement:'
					))
			->getForm();
		return $this->render('wbriksBackendBundle:arrangements:editArrangement.html.twig', array('form' => $form->createView()));
	}

	/**
	 * create a new arrangement
	 */
	public function createAction(Request $request){
		$arrangement = new Arrangement();
		$form = $this->createFormBuilder($arrangement)
					->add('name', 'text')
					->add('countries', 'entity', array(
							'multiple' => true,
							'expanded' => false,
							'property' => 'name',
							'class'    => 'wbriks\BackendBundle\Entity\Country',
                            'query_builder' => function(\wbriks\BackendBundle\Entity\CountryRepository $er) {
                                return $er->createQueryBuilder('u')
                                    ->orderBy('u.name', 'ASC');
                            },
							'attr'     => array('size' => 20),
							'label'    => 'Members of this arrangement:'
						))
					->getForm();
		if ($request->isMethod('POST')) {
        	$form->bind($request);
        	if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
    			$em->persist($arrangement);
    			$em->flush();
				return $this->redirect($this->generateUrl('wbriks_arrangements_list'));
			}
    	}
		return $this->render('wbriksBackendBundle:arrangements:newArrangement.html.twig', array('form' => $form->createView()));
	}

}
