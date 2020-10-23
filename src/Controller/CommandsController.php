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

class CommandsController extends AbstractController
{
    /*public function facture(SessionInterface $session, ProductsRepository $productsRepo)
    {
        $generator = $this->container->get('security.secure_random');
        $panier = $session->get('panier');
        $commande = [];
        $totalHT = 0;
        $totalTTC = 0;
        $products = $productsRepo->findArray(array_keys($session->get('panier')));

        foreach ($products as $product) {
            $prixHT = ($product->getPrice() * $product->getQuantity());
            $prixTTC = ($prixHT / 100 * 20);
            $totalHT += $prixHT;
            $totalTTC += $prixTTC;

            $commande['produit'][$product->getId()] = [
                'reference' => $product->getTitle(),
                'quantite' => $panier[$product->getQuantity()],
                'prixHT' => number_format($product->getPrice(), 2),
                'prixTTC' => number_format($prixHT / 100 * 20, 2)
            ];

            $commande['prixHT'] = number_format($totalHT, 2);
            $commande['prixTTC'] = number_format($totalTTC, 2);
            $commande['token'] = bin2hex($generator->nextBytes(20));

            return $commande;
        }
    }*/

    /**
     *@Route("/command/{id<[0-9]+>}", name="app_command")
     */
    /*public function prepareCommandAction(SessionInterface $session, EntityManagerInterface $em, CommandRepository $commandRepo)
    {
        if(!$session->has('commande')){
            $commande = new Command();
        }else{
            $commande = $commandRepo->find($session->get('commande'));

            $user = $this->getUser();// Récupère l'utilisateur pour pouvoir le setté dans le setUser

            $commande->setUser($user->getfullName())
                    ->setReference(0)
                    ->setValidate(0)
                    ->setCommand($this->facture());
        }

        if(!$session->has('commande')){
            $em->persist($commande);
            $session->set('commande', $commande);
        }

        $em->flush();

        return new Response($commande->getId());
    }*/
}
