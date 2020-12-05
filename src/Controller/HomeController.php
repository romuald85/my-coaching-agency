<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(ProductsRepository $productsRepo): Response
    {
        $products = $productsRepo->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
