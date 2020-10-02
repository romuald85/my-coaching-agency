<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/add", name="app_comment_add", methods={"GET", "POST"})
     */
    public function addComment(Request $request, EntityManagerInterface $em)
    {
        $comments = new Comments();

        $form = $this->createForm(CommentsType::class, $comments);

        $form->handleRequest($request);
        dd($comments->setUser($this->getUser()));
        if($form->isSubmitted() && $form->isValid()){
            $comments->setUser($this->getUser());
            $comments->getArticles();
            $em->persist($comments);
            $em->flush();

            $this->addFlash('succes', 'Le commentaire a été ajouté');

            return $this->redirectToRoute('app_blog');
        }

                        
        return $this->render('comment/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
