<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\DevisProduits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AfficherDevisDetailController extends AbstractController
{
    #[Route('/devis/{id}', name: 'app_devis_recap')]
    public function recapDevis(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le devis et les produits associés
        $devis = $entityManager->getRepository(Devis::class)->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Le devis demandé n\'existe pas.');
        }

        // Calculer les détails du devis
        $devisProduits = $devis->getDevisProduits();
        $totalQuantite = 0;
        $totalPrix = 0;

        foreach ($devisProduits as $devisProduit) {
            $totalQuantite += $devisProduit->getQuantite();
            $totalPrix += $devisProduit->getPrixTotal();
        }

        return $this->render('devis_recap.html.twig', [
            'devis' => $devis,
            'devisProduits' => $devisProduits,
            'totalQuantite' => $totalQuantite,
            'totalPrix' => $totalPrix,
        ]);
    }
}
