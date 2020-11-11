<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/users", name="app_users")
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
     * @Route("/admin/users/edit/{id<[0-9]+>}", name="app_users_edit")
     */
    public function editUser(Request $request, User $user, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', "L'utilisateur {$user->getfullName()} a été  modifié");

            return $this->redirectToRoute('app_users');
        }

        return $this->render('admin/edit_users.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/command/{id<[0-9]+>}", name="app_admin_command")
     */
    public function usersListCommand(CommandRepository $commandRepository)
    {
        $commands = $commandRepository->findBy([ ]);

        return $this->render('admin/command_user.html.twig', [
            'commands' => $commands
        ]);
    }
}
