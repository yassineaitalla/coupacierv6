<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeFournisseurController extends AbstractController
{
    #[Route('/commande-fournisseur', name: 'app_commande_fournisseur')]
    public function CommandeFournisseur(): Response
    {
        return $this->render('CommandeFournisseur.html.twig');
    }

   
}
