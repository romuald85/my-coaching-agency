<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/article/{id<[0-9]+>}/comments_approve", name="app_comments_approve", methods={"GET", "POST"})
     */
    public function approveComment(Article $article)
    {
        return $this->render('comments/approve.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/{id<[0-9]+>}/comments_delete", name="app_comments_delete", methods={"DELETE"})
     */
    public function deleteComment(Comments $comment, EntityManagerInterface $em)
    {
        //dd($comment->getArticles());
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('app_article');
        /*if($this->isCsrfTokenValid('comment_deletion_' . $comment->getId(), $request->request->get('csrf_token'))){
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', 'Le commentaire a bien été supprimé');
        }
        
        return $this->redirectToRoute('app_comments_delete' . $article->getId());*/
    }
}
