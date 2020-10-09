<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comments;
use App\Form\ApproveCommentType;
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
     * @Route("/article/{id<[0-9]+>}/comments_admin", name="app_comments_admin", methods={"GET", "POST"})
     */
    public function showCommentInAdmin(Article $article)
    {
        return $this->render('comments/comments_admin.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/{id<[0-9]+>}/comments_approve", name="app_comments_approve", methods={"GET", "POST"})
     */
    public function approveComment(Comments $comment, EntityManagerInterface $em): Response
    {
            $comment->setApproveComment(true);
            $em->persist($comment);
            $em->flush();

            $this->AddFlash('success', "Le commentaire de {$this->getUser()->getfullName()} a bien été approuvé");

            return $this->redirectToRoute('app_comments_admin', [
                'id' => $comment->getArticles()->getId()
            ]);
    }

    /**
     * @Route("/{id<[0-9]+>}/comments_delete", name="app_comments_delete", methods={"DELETE"})
     */
    public function deleteComment(Request $request, Comments $comment, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('comment_deletion_' . $comment->getId(), $request->request->get('csrf_token'))){
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', 'Le commentaire a bien été supprimé');
        }

        return $this->redirectToRoute('app_comments_admin', [
            'id' => $comment->getArticles()->getId()
        ]);
    }
}
