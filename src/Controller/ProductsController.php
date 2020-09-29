<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * Affiche la liste des produits du côté admin
     *
     * @Route("/admin/products", name="app_products")
     */
    public function index(ProductsRepository $productsRepo)
    {
        $products = $productsRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     *@Route("/admin/products/create", name="app_create_products")
     */
    public function createProduct(Request $request, EntityManagerInterface $em)
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
}
