<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * Affiche la liste des produits du côté admin
     *
     * @Route("/admin/products", name="app_products", methods="GET")
     */
    public function index(ProductsRepository $productsRepo)
    {
        $products = $productsRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     *@Route("/admin/products/create", name="app_create_products", methods={"GET", "POST"})
     */
    public function createProducts(Request $request, EntityManagerInterface $em)
    {
        $product = new Products();

        $form = $this->createForm(ProductsType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté');

            return $this->redirectToRoute('app_products');
        }

        return $this->render('admin/create_product.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/products/{id<[0-9]+>}", name="app_products_show", methods="GET")
     */
    public function show(Products $products): Response
    {
        return $this->render('admin/show_products.html.twig', compact('products'));
    }

    /**
     * @Route("/admin/products/{id<[0-9]+>}/edit", name="app_products_edit", methods={"GET", "PUT"})
     */
    public function editProducts(Products $products, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProductsType::class, $products, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', 'Produit modifié');

            return $this->redirectToRoute('app_products');
        }

        return $this->render('admin/edit_products.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
}
