<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\ContactFormType;

class DefaultController extends Controller
{
  const ARTICLE_LIMIT = 5;

  /**
   * Lists 5 latest articles
   * in createdAt desc order
   * 
   * @Route("/", name="homepage")
   * @Method("GET")
   */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $articles = $em->getRepository('AppBundle:Article')->findBy(
      array(),                             // Critere
      array('createdAt' => 'desc'),        // Tri
      static::ARTICLE_LIMIT,                 // Limite
      0                                    // Offset
    );

    return $this->render('default/index.html.twig', array(
      'articles' => $articles,
    ));
  }

  /**
   * Contact form : send an email to webmaster
   * @Route("/contact", name="contact")
   * @Method({"GET", "POST"})
   */
  public function sendEmailAction(Request $request, \Swift_Mailer $mailer)
  {
    $form = $this->createForm(ContactFormType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      // Message with swiftmailer
      // Send an email from user's email
      // to webmaster email
      // in html, utf8
      $message = (new \Swift_Message('Un nouveau message ! '))
        ->setFrom( $form['email']->getData() )
        ->setTo($this->container->getParameter('mailer_user'))
        ->setCharset('utf-8')
        ->setContentType('text/html')
        ->setBody(
          $this->renderView(
              'Emails/contact.html.twig',
              [
                'message' => $form['message']->getData(),
                'username' => $form['username']->getData()
              ]
          ),
          'text/html'
        )
      ;

      $mailer->send($message).

      $this->addFlash('success', 'Votre email a été envoyé');
      return $this->redirectToRoute('contact');
      
    }

    return $this->render('default/contact.html.twig', array(
      'form' => $form->createView(),
    ));
  }

}
