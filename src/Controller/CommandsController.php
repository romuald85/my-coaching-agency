<?php

namespace App\Controller;

use App\Entity\Command;
use App\Services\Cart\GetReference;
use App\Repository\CommandRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandsController extends AbstractController
{
    protected $session;
    protected $em;
    protected $productsRepo;
    protected $commandRepo;
    protected $getReference;

    public function __construct(SessionInterface $session, EntityManagerInterface $em, ProductsRepository $productsRepo, CommandRepository $commandRepo, GetReference $getReference)
    {
        $this->session = $session;
        $this->em = $em;
        $this->productsRepo = $productsRepo;
        $this->commandRepo = $commandRepo;
        $this->getReference = $getReference;
    }

    public function prepareCommandAction()
    {
        $user = $this->getUser();
        $command = new Command();

        if(!$this->session->has('panier')){
            return $this->redirectToRoute('app_cart');
        }
        
        $panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $this->productsRepo->find($id)->getTitle(),
                'price' => $this->productsRepo->find($id)->getPrice(),
                'prixHT' => $this->productsRepo->find($id)->getPrice() * $quantity,
                'TVA' => number_format(($this->productsRepo->find($id)->getPrice() * $quantity) / 100 * 20, 2),
                'prixTTC' => number_format($this->productsRepo->find($id)->getPrice() * $quantity + ($this->productsRepo->find($id)->getPrice() * $quantity) / 100 * 20, 2),
                'quantity' => $quantity
            ];
        }

        $command->setName($user->getfullName())
                ->setReference($this->getReference->reference())
                ->setCommand($panierWithData)
                ->setValidate(1)
                ->setDate(new \DateTime())
                ->setUser($this->getUser());
        

        $this->em->persist($command);
        $this->em->flush();

        //$this->session->remove('panier');


        return $panierWithData;
    }
}
