<?php

namespace App\Services;

use App\Repository\CommandRepository;

class GetReference
{
    private $commandRepo;

    public function __construct(CommandRepository $commandRepo)
    {
        $this->commandRepo = $commandRepo;
    }

    /**
     * Service qui sert à incrémenter la référence de la commande à la validation du panier
     */
    public function reference()
    {
        $reference = $this->commandRepo->findOneBy(['validate' => 1], ['id' => 'DESC'], 1, 1);

        if(!$reference){// S'il n'y a pas de référence mettre la première référence à 1
            return 1;
        }else{
            return $reference->getReference() + 1;
        }
    }
}