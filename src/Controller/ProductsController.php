<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    public function index()
    {        
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'ProductsController'
        ]);
    }
}
