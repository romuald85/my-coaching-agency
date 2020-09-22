<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * Modifier le profil de l'utilisateur
     * 
     * @Route("/profile/edit/{id<[0-9]+>}", name="app_profile_edit")
     */
    public function editProfile(Request $request, EntityManagerInterface $em, User $user)
    {
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', 'Les informations ont bien été modifiées');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
