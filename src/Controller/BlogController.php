<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/blog/musculation", name="blog_musculation")
     */
    public function blog_musculation()
    {
        $articles = $this->articleRepository->findBy(['category' => 'musculation'], ['createdAt' => 'DESC']);

        return $this->render('blog/blog_musculation.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/blog/street-workout", name="blog_street_workout")
     */
    public function blog_street_workout()
    {
        $articles = $this->articleRepository->findBy(['category' => 'street-workout'], ['createdAt' => 'DESC']);

        return $this->render('blog/blog_street_workout.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/blog/crossfit", name="blog_crossfit")
     */
    public function blog_crossfit()
    {
        $articles = $this->articleRepository->findBy(['category' => 'crossfit'], ['createdAt' => 'DESC']);

        return $this->render('blog/blog_crossfit.html.twig', [
            'articles' => $articles,
        ]);
    }
}
