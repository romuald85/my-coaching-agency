<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comments;
use App\Entity\PostLike;
use App\Form\ArticleType;
use App\Form\CommentsType;
use App\Repository\ArticleRepository;
use App\Repository\PostLikeRepository;
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
        $article1 = $articleRepository->findOneBy([
            'category' => 'musculation',
        ], ['createdAt' => 'DESC']);

        $article2 = $articleRepository->findOneBy([
            'category' => 'street-workout',
        ], ['createdAt' => 'DESC']);

        $article3 = $articleRepository->findOneBy([
            'category' => 'crossfit'
        ], ['createdAt' => 'DESC']);

        return $this->render('article/blog.html.twig', [
            'article1' => $article1,
            'article2' => $article2,
            'article3' => $article3
        ]);
    }

    /**
     * @Route("/blog/{id<[0-9]+>}", name="app_blog_article_show", methods={"GET", "POST"})
     */
    public function showInBlog(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();// Récupère l'utilisateur pour pouvoir le setté dans le setAuthor

        $comments = new Comments();

        $form = $this->createForm(CommentsType::class, $comments);
        

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comments->setUser($this->getUser())
                     ->setArticles($article)
                     ->setApproveComment(false)
                     ->setAuthor($user->getfullName());
            $em->persist($comments);
            $em->flush();

            $this->addFlash('success', 'Le commentaire est en attente de validation auprès du modérateur');

            return $this->redirectToRoute('app_blog_article_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/show_in_blog.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de liker ou de unliker un article
     *
     * @Route("/blog/{id<[0-9]+>}/like", name="app_blog_like")
     * 
     * @param Article $article
     * @param EntityManagerInterface $em
     * @param PostLikeRepository $postLikeRepo
     * @return Response
     */
    public function like(Article $article, EntityManagerInterface $em, PostLikeRepository $postLikeRepo): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->json([
                'code' => 403,
                'message' => 'Non autorisé'
            ], 403);
        }

        if($article->isLikeByUser($user)){
            $like = $postLikeRepo->findOneBy([
                'post' => $article,
                'user' => $user
            ]);

            $em->remove($like);
            $em->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Le like a bien été supprimé',
                'likes' => $postLikeRepo->count(['post' => $article])
            ], 200);
        }

        $like = new PostLike;
        $like->setPost($article)
             ->setUser($user);
             
        $em->persist($like);
        $em->flush();

        return $this->json([
            'code' => 200, 
            'message' => 'Le like a bien été ajoué',
            'likes' => $postLikeRepo->count(['post' => $article  ])
        ], 200);
    }
}
