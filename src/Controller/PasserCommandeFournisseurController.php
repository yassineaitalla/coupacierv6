<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PasserCommandeFournisseurController extends AbstractController
{
    #[Route('/passer/commande/fournisseur', name: 'app_passer_commande_fournisseur')]
    public function index(): Response
    {
        return $this->render('passercommandefournisseur.html.twig', [
            'controller_name' => 'PasserCommandeFournisseurController',
        ]);
    }
}
