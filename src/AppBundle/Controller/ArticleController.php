<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Article controller.
 *
 * @Route("article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/", name="article_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:Article')->findAllQuery();

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,                             /* query NOT result */
            $request->query->getInt('page', 1)  /*page number*/,
            10                                  /*limit per page*/
        );

        return $this->render('article/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/new", name="article_new")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            // Flashbag success message
            $this->addFlash('success', 'Article correctement enregistré');

            return $this->redirectToRoute('article_show', array('id' => $article->getId()));
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="article_show", requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        return $this->render('article/show.html.twig', array(
            'article' => $article
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/edit", name="article_edit", requirements={"id"="\d+"})
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {   
        // Check if the author of the article is the current user
        // else, redirect to profile
        $user = $this->getUser();
        $articleUser = $article->getUser();

        if ($user != $articleUser){
            $this->addFlash("warning", "Vous n'êtes pas l'auteur de cet article, vous ne pouvez donc pas le modifier.");
            return $this->redirectToRoute('user_index');
        }

        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            // Flashbag success message
            $this->addFlash('success', 'Article correctement mis à jour');

            return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}/delete", name="article_delete")
     * @Security("has_role('ROLE_USER')")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Article $article)
    {
        // Check if the author of the article is the current user
        // else, redirect to profile
        $user = $this->getUser();
        $articleUser = $article->getUser();

        if ($user != $articleUser){
            $this->addFlash("warning", "Vous n'êtes pas l'auteur de cet article, vous ne pouvez donc pas le supprimer.");
            return $this->redirectToRoute('user_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        // Flashbag success message
        $this->addFlash('success', 'Article correctement supprimé');

        return $this->redirectToRoute('user_index');
    }
}
