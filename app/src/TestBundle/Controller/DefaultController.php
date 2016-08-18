<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use TestBundle\Entity\Feedback;

class DefaultController extends Controller
{

    /**
     * @Route("/", )
     */
    public function indexAction(Request $request)
    {
        $feedback = new Feedback();

        $form = $this->createFormBuilder($feedback)

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
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'datepicker'),
                )
            )
            ->add('file', FileType::class)

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $feedback = $form->getData();

             $em = $this->getDoctrine()->getManager();
             $em->persist($feedback);
             $em->flush();

            return $this->redirectToRoute('feedback_success',
                array('date' => $feedback->getDate()->format('Y-m-d'))
            );
        }

        return $this->render('TestBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/success/{date}", name="feedback_success")
     */
    public function successAction($date){

        return $this->render('TestBundle:Default:feedback_success.html.twig', array(
            'date' => $date
        ));
    }
}
