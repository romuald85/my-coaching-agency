<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="app_admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/users", name="app_users")
     */
    public function usersList(UserRepository $users)
    {
        return $this->render('admin/users.html.twig',[
            'users' => $users->findAll()
        ]);
    }

    /**
     * Pour modifier l'utilisateur
     *
     * @Route("/users/edit/{id<[0-9]+>}", name="app_users_edit")
     */
    public function editUser(Request $request, User $user, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('succes', "L'utilisateur {$user->getfullName()} a été  modifié");

            return $this->redirectToRoute('app_users');
        }

        return $this->render('admin/edit_users.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
