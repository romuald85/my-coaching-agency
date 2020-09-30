<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article", methods="GET")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('article/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/article/create", name="app_article_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article->setUser($this->getUser());
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article ajouté');

            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{id<[0-9]+>}", name="app_article_show", methods="GET")
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', compact('article'));
    }

    /**
     * @Route("/article/{id<[0-9]+>}/edit", name="app_article_edit", methods={"GET", "PUT"})
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', "L' article {$article->getTitle()} a été modifié");

            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/article/{id<[0-9]+>}/delete", name="app_article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('article_deletion_' . $article->getId(), $request->request->get('csrf_token'))){
            $em->remove($article);
            $em->flush();

            $this->addFlash('info', 'Article supprimé');
        }

        return $this->redirectToRoute('app_article');
    }

    /**
     * @Route("/blog", name="app_blog", methods="GET")
     */
    public function blog(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('article/blog.html.twig', compact('articles'));
    }
}
