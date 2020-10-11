<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * Affiche les produits dans le panier
     * @Route("/panier", name="app_cart")
     */
    public function index(SessionInterface $session, ProductsRepository $productsRepo)
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $productsRepo->find($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id<[0-9]+>}", name="app_cart_add")
     */
    public function add($id, SessionInterface $session)
    {        
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
    }

}
