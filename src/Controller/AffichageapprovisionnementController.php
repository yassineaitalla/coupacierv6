<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AffichageapprovisionnementController extends AbstractController
{
    #[Route('/affichageapprovisionnement', name: 'app_affichageapprovisionnement')]
    public function index(): Response
    {
        return $this->render('affichageapprovisionnement/index.html.twig', [
            'controller_name' => 'AffichageapprovisionnementController',
        ]);
    }
}
