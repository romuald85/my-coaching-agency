<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * Liste des programmmes achetés par l'utilisateur et sa quantité
     * @Route("/admin/users_programs", name="app_users_programs")
     */
    public function productsSold(ProductsRepository $products)
    {
        return $this->render('admin/users_programs.html.twig', [
            'products' => $products->findAll()
        ]);
    }
}
