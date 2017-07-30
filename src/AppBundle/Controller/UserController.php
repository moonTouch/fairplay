<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/user")
 * use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Find articles created by current user
        $articles = $em->getRepository('AppBundle:Article')->findByUser($this->getUser());

        return $this->render('user/index.html.twig', array(
            'articles' => $articles,
        ));
    }
}
