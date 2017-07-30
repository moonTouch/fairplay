<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Article;

/**
 *  Set User in article entity when prePersist
 */
class setUserToArticleListener
{
    private $tokenStorage;
 
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        // If entity is not an Article
        // or
        // if user is not logged in (in fixtures cases)
        // do nothing
        // else set current user as article user
        // 
        if (!$entity instanceof Article || !($this->tokenStorage->getToken())) {

            return;
        
        } 
   
        $entity->setUser($this->tokenStorage->getToken()->getUser());

    }
}
