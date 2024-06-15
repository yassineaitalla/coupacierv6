<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AfficherPageCommandeController extends AbstractController
{
    #[Route('/commander', name: 'vacommandes')]
    public function Commander(): Response
    {
        return $this->render('pagecommande.html.twig', [
            'message' => 'Bienvenue sur la page de commande !',
        ]);
    }
}
