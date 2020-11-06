<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Form\BillType;
use App\Services\Cart\CartServices;
use App\Controller\CommandsController;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/command", name="app_validate_command", methods={"GET", "POST"})
     */
    public function validateAction(CartServices $cartServices, CommandsController $commandsController, EntityManagerInterface $em, Request $request, ProductsRepository $productsRepo): Response
    {
        
        $user = $this->getUser();
        $bill = new Bill();
        
        
        $form = $this->createForm(BillType::class, $bill, [
            'method' => 'POST'
            ]);
            
            $form->handleRequest($request);
            
            $panier = $user->getCart();

            $quantityItems = array_sum($this->getUser()->getCart()->getContent());// Calcul la somme des valeurs d'un tableau associatif pour afficher la somme des produits dans mon panier
            
            $totalHT = $cartServices->getTotal($this->getUser())[0];
            $totalTTC = $cartServices->getTotal($this->getUser())[1];
            $tva = $cartServices->getTotal($this->getUser())[2];

        $panierWithData = [];

        foreach ($panier->getContent() as $id => $quantity) {
            $panierWithData[] = [
                'product' => $productsRepo->find($id)->getTitle(),
                'price' => $productsRepo->find($id)->getPrice(),
                'prixHT' => $productsRepo->find($id)->getPrice() * $quantity,
                'TVA' => number_format(($productsRepo->find($id)->getPrice() * $quantity) / 100 * 20, 2),
                'prixTTC' => number_format($productsRepo->find($id)->getPrice() * $quantity + ($productsRepo->find($id)->getPrice() * $quantity) / 100 * 20, 2),
                'quantity' => $quantity
            ];
        }
            
        if($form->isSubmitted() && $form->isValid()){
            $bill->setProducts($panierWithData);
                
            $em->persist($bill);
            $em->flush();

            $commandsController->prepareCommandAction();// valider la commande préparée de la page panier

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('commands/index.html.twig', [
            'items' => $panierWithData,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'tva' => $tva,
            'quantityItems' => $quantityItems,
            'form' => $form->createView()
        ]);
    }
}
