<?php 

namespace App\Services\Cart;

use App\Entity\CartPersist;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CartPersistRepository;

class CartServices
{
    protected $productsRepo;
    protected $cartRepo;
    protected $em;

    public function __construct(ProductsRepository $productsRepo, CartPersistRepository $cartRepo, EntityManagerInterface $em)
    {
        $this->productsRepo = $productsRepo;
        $this->cartRepo = $cartRepo;
        $this->em = $em;
    }


    public function addProductToCart($user, $id)
    {
        // Récupérer le panier de l'utilisateur connecté
        $panier = ($user->getCart()) ? $user->getCart() : $user->setCart(new CartPersist())->getCart();

        // Récuperer le contenu du panier
        $content = $panier->getContent();

        // Vérifier si le produit existe déjà dans le panier
        if(isset($content[$id])){
            $content[$id]++;
        }else{
            $content[$id] = 1;
        }
        
        // On sauvegarde le contenu dans le panier
        $panier->setContent($content);
        $this->em->persist($panier);
        $this->em->flush();
    }

    public function removeProductToCart($user, $id)
    {
        $panier = ($user->getCart()) ? $user->getCart() : $user->setCart(new CartPersist())->getCart();

        $content = $panier->getContent();

        if(isset($content[$id])){
            $content[$id]--;
        }

        if($content[$id] == 0){
            unset($content[$id]);
        }

        $panier->setContent($content);
        $this->em->persist($panier);
        $this->em->flush();
    }

    public function getFullCart($user)
    {
        $panier = ($user->getCart()) ? $user->getCart() : $user->setCart(new CartPersist())->getCart();

        $panierWithData = [];
        
        
        foreach ($panier->getContent() as $id => $quantity){
            $panierWithData[] = [
                'product' => $this->productsRepo->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }

    public function getTotal($user)
    {
        $totalHT = 0;
        $totalTTC = 0;
        $tva = 0;

        foreach ($this->getFullCart($user) as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $totalHT += $totalItem;
        }

        $totalTTC = $totalHT * 1.2;
        $tva = ($totalHT / 100) * 20;

        return [$totalHT, $totalTTC, $tva];
    }
}