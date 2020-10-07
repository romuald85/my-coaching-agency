<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentsController extends AbstractController
{
    /**
     * @Route("/comments", name="comments")
     */
    public function index()
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    }

    /**
     * @Route("/article/{id<[0-9]+>}/comments_approve", name="app_comments_approve")
     */
    public function approveComment(Article $article)
    {
        return $this->render('comments/approve.html.twig', [
            'article' => $article
        ]);
    }
}
