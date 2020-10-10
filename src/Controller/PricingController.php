<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PricingController extends AbstractController
{
    /**
     * Affiche les produits (programmes dans la page pricing là où il y a les programmes)
     * @Route("/pricing", name="app_pricing")
     */
    public function index(ProductsRepository $productsRepo)
    {
        $products = $productsRepo->findAll();

        return $this->render('pricing/index.html.twig', [
            'products' => $products
        ]);
    }

    public function showProductsInPricingPage()
    {

    }
}
