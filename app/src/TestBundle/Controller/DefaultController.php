<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use TestBundle\Entity\Feedback;

class DefaultController extends Controller
{
//    /**
//     * @Route("/")
//     */
//    public function indexAction()
//    {
//        return $this->render('TestBundle:Default:index.html.twig');
//    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $feedback = new Feedback();

        $form = $this->createFormBuilder($feedback)
            ->setAction($this->generateUrl('test_default_index'))
            ->add('name', null)
            ->add('age', null)
            ->add('email', null)
            //->add('file', FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feedback = $form->getData();

             $em = $this->getDoctrine()->getManager();
             $em->persist($feedback);
             $em->flush();

            return $this->redirectToRoute('feedback_success'
          //      , array('date' => $feedback)
            );
        }

        return $this->render('TestBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
