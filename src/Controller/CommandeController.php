<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande')]
    public function index(Request $request): Response
    {
        // Votre logique de gestion du formulaire ici, si nécessaire

        return $this->render('commande.html.twig');
    }
}
