<?php

namespace App\Controller;

use App\Services\Cart\CartServices;
use App\Controller\CommandsController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="app_cart")
     */
    public function index(CartServices $cartServices, SessionInterface $session, CommandsController $commandsController)
    {
        $panierWithData = $cartServices->getFullCart($this->getUser());
        
        $totalHT = $cartServices->getTotal($this->getUser())[0];
        $totalTTC = $cartServices->getTotal($this->getUser())[1];
        $tva = $cartServices->getTotal($this->getUser())[2];

        
        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'tva' => $tva,
        ]);
    }
    
    /**
     * @Route("/panier/ajouter/{id<[0-9]+>}", name="app_cart_add")
     */
    public function add($id, CartServices $cartServices)
    {
        $cartServices->addProductToCart($this->getUser(), $id);

        $this->addFlash('success', 'Le produit a bien été ajouté au panier');

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Supprime un produit du panier
     * 
     * @Route("/panier/supprimer/{id<[0-9]+>}", name="app_cart_remove")
     */
    public function remove($id, CartServices $cartServices)
    {
        $cartServices->removeProductToCart($this->getUser(), $id);

        $this->addFlash('success', 'Le produit a bien été supprimé du panier');

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Valider le panier
     * 
     * @Route("/command", name="app_validate_command")
     */
    public function validateAction(CommandsController $commandsController): Response
    {
        $commandsController->prepareCommandAction();

        return $this->render('commands/index.html.twig', [
            'items' => $this->getUser()->getCart()
        ]);
    }
}
