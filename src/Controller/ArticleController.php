<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($article);
            $em->flush();

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
     * @Route("/article/{id<[0-9]+>}/edit", name="app_article_edit", methods={"GET", "POST"})
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);

    }
}
