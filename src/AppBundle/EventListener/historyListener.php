<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 *  Listen controller, login and logout actions
 *  Creates an historic of user's activity
 */
class historyListener implements LogoutHandlerInterface
{
    private $em;
    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(
        EntityManagerInterface $em, 
        TokenStorageInterface $tokenStorage, 
        AuthorizationCheckerInterface $authorizationChecker
    )
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * When route "article_show", if user is logged, add a relation between current user and the viewed article
     * @param FilterControllerEvent $event [description]
     */
    public function addViewedArticle(FilterControllerEvent $event)
    {
        $requestAttributes = $event->getRequest()->attributes;

        // Check if route is article_show 
        // and if user has role ROLE_USER
        if ( ("article_show" === $requestAttributes->get('_route')) && ($this->authorizationChecker->isGranted('ROLE_USER')) ){

            $user = $this->tokenStorage->getToken()->getUser();

            // Find the id article passed in url parameter
            $articleId = $requestAttributes->get('_route_params')['id'];
            // Find the article object associed
            $article = $this->em->getRepository('AppBundle:Article')->findOneById($articleId);

            // If article doesn't exists
            if (!$article) {
                return;
            }

            // If current user has not viewed this article yet, add it in his historic
            if (!$user->getViewedArticles()->contains($article)){

                $user->addViewedArticle($article);
                $this->em->persist($user);
                $this->em->flush();
            }                    
        }  
    }

    /**
     * When user is succesfully logged in, before fos_user update last_login, assign last_login to historic_login 
     */
    public function setHistoricLogin()
    {   
        if ($this->tokenStorage->getToken()){

            $user = $this->tokenStorage->getToken()->getUser();

            $user->setHistoricLogin($user->getLastLogin());

            $this->em->persist($user);
            $this->em->flush();

        }
    }

    /**
     * When user logout, assign current DateTime to historicLogout 
     */
    public function logout(Request $Request, Response $Response, TokenInterface $Token) 
    {

        if ($this->tokenStorage->getToken()){

            $user = $this->tokenStorage->getToken()->getUser();

            $user->setHistoricLogout(new \Datetime);

            $this->em->persist($user);
            $this->em->flush();
        }
    }
}
