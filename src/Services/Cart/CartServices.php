<?php 

namespace App\Services\Cart;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices
{
    protected $session;
    protected $productsRepo;

    public function __construct(SessionInterface $session, ProductsRepository $productsRepo)
    {
        $this->session = $session;
        $this->productsRepo = $productsRepo;
    }

    public function addProductToCart($id)
    {
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function removeProductToCart($id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])){
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function getFullCart()
    {
        $panier = $this->session->get('panier', []);

        $panierWithData = [];
        
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'product' => $this->productsRepo->find($id),
                'quantity' => $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal()
    {
        $totalHT = 0;
        $totalTTC = 0;
        $tva = 0;

        foreach ($this->getFullCart() as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $totalHT += $totalItem;
        }

        $totalTTC = $totalHT * 1.2;
        $tva = ($totalHT / 100) * 20;

        return [$totalHT, $totalTTC, $tva];
    }
}