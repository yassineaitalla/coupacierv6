<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AfficherPageApprovisionnementController extends AbstractController
{
    #[Route('/afficher/page/approvisionnement', name: 'app_afficher_page_approvisionnement')]
    public function index(): Response
    {
        return $this->render('serviceaprovisionnement.html.twig', [
            'controller_name' => 'AfficherPageApprovisionnementController',
        ]);
    }
}
