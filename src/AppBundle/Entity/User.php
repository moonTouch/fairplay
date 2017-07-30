<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="user")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Article", cascade={"persist", "remove"})
     */
    private $viewedArticles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="historic_login", type="datetime", nullable=true, options={"default" : null} )
     */
    private $historicLogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="historic_logout", type="datetime", nullable=true, options={"default" : null} )
     */
    private $historicLogout;

    public function __construct()
    {
        parent::__construct();
        
        $this->articles = new ArrayCollection();
        $this->roles = array('ROLE_USER');
    }
    
    /**
     * Add article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return User
     */
    public function addArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \AppBundle\Entity\Article $article
     */
    public function removeArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
    
    /**
     * Add viewedArticle
     *
     * @param \AppBundle\Entity\Article $viewedArticle
     *
     * @return User
     */
    public function addViewedArticle(\AppBundle\Entity\Article $viewedArticle)
    {
        
        $this->viewedArticles[] = $viewedArticle;

        return $this;
    }

    /**
     * Remove viewedArticle
     *
     * @param \AppBundle\Entity\Article $viewedArticle
     */
    public function removeViewedArticle(\AppBundle\Entity\Article $viewedArticle)
    {
        $this->viewedArticles->removeElement($viewedArticle);
    }

    /**
     * Get viewedArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getViewedArticles()
    {
        return $this->viewedArticles;
    }

    /**
     * Set historicLogin
     * 
     * @param \DateTime $historicLogin
     *
     * @return User
     */
    public function setHistoricLogin($historicLogin = null)
    {
        $this->historicLogin = $historicLogin;

        return $this;
    }

    /**
     * Get historicLogin
     *
     * @return \DateTime
     */
    public function getHistoricLogin()
    {
        return $this->historicLogin;
    }

    /**
     * Set historicLogout
     *
     * @param \DateTime $historicLogout
     *
     * @return User
     */
    public function setHistoricLogout($historicLogout = null)
    {
        $this->historicLogout = $historicLogout;

        return $this;
    }

    /**
     * Get historicLogout
     *
     * @return \DateTime
     */
    public function getHistoricLogout()
    {
        return $this->historicLogout;
    }

}
