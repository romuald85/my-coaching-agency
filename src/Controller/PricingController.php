<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * Affiche le produit côté client avant de l'acheter
     * @Route("/pricing/{id<[0-9]+>}", name="app_show_product_to_buy", methods={"GET", "POST"})
     */
    public function showProductToBuy(Products $product): Response
    {
        return $this->render('pricing/show_products_to_buy.html.twig', [
            'product' => $product
        ]);
    }
}
