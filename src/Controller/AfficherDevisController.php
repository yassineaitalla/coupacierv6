<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfficherDevisController extends AbstractController
{
    #[Route('/devis', name: 'app_afficher_devis')]
    public function afficherDevis(Devis $devis): Response
    {
        return $this->render('affichagedevis.html.twig', [
            'devis' => $devis,
        ]);
    }
}
