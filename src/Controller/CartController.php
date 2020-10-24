<?php

namespace App\Controller;

use App\Entity\Command;
use App\Repository\CommandRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\GetReference;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="app_cart")
     */
    public function index(SessionInterface $session, ProductsRepository $productsRepo)
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];
        
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'product' => $productsRepo->find($id),
                'quantity' => $quantity
            ];
        }
        
        //dd($panierWithData);

        $totalHT = 0;
        $totalTTC = 0;
        $tva = 0;

        foreach ($panierWithData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $totalHT += $totalItem;
        }

        $totalTTC = $totalHT * 1.2;
        $tva = ($totalHT / 100) * 20;

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
    public function add($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $this->addFlash('success', 'Le produit a bien été ajouté au panier');

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/panier/supprimer/{id<[0-9]+>}", name="app_cart_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        $this->addFlash('success', 'Le produit a bien été supprimé du panier');

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/command", name="app_validate_command")
     */
    public function validateAction(SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepo, CommandRepository $commandRepo, GetReference $getReference): Response
    {
        $user = $this->getUser();
        $command = new Command();

        if(!$session->has('panier')){
            return $this->redirectToRoute('app_cart');
        }
        
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $productsRepo->find($id)->getTitle(),
                'quantity' => $quantity
            ];
        }

        $command->setName($user->getfullName())
                ->setReference($getReference->reference())
                ->setCommand($panierWithData)
                ->setQuantity(1)
                ->setValidate(1)
                ->setDate(new \DateTime())
                ->setUser($this->getUser());
        

        $em->persist($command);
        $em->flush();
        //$session->remove('panier');

        return $this->render('commands/index.html.twig', [
            'items' => $panierWithData
        ]);
    }
}
