<?php

namespace App\Controller;

use App\Entity\Devis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AfficherDevisController extends AbstractController
{
    #[Route('/devis', name: 'app_afficher_devis')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $devisRepository = $entityManager->getRepository(Devis::class);
        $devis = $devisRepository->findAll();

        return $this->render('affichagedevis.html.twig', [
            'devis' => $devis,
        ]);
    }
}

    

   