<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('article/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/article/{id<[0-9]+>}", name="app_articles_show")
     */
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', compact('article'));
    }
}
