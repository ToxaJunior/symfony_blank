<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use TestBundle\Entity\Feedback;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $feedback = new Feedback();

        $form = $this->createFormBuilder($feedback)
            ->setAction($this->generateUrl('test_default_index'))
            ->add('name', null, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'введите имя'
            )))
            ->add('age', null, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'введите возраст'
            )))
            ->add('email', null, array('attr' => array(
                'class' => 'form-control',
                'placeholder' => 'введите email'
            )))
            ->add('date', DateType::class,
                array(
//                'widget' => 'single_text',
//                'html5' => false,
//                'attr' => array('class' => 'js-datepicker'),
                )
            )
         //   ->add('file', FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit',
                'attr' => array('class' => 'btn-success')))
            ->getForm();

        //var_dump($form->getData());die();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feedback = $form->getData();

             $em = $this->getDoctrine()->getManager();
             $em->persist($feedback);
             $em->flush();

//            return $this->redirectToRoute('feedback_success'
//            );
        }

        return $this->render('TestBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'data' => $request
        ));
    }

    /**
     * @Route("/success", name="feedback_success")
     */
    public function successAction(){

        return $this->render('TestBundle:Default:feedback_success.html.twig', array());
    }
}
