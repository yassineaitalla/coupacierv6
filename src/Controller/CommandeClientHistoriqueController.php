<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commande;

use Doctrine\ORM\EntityManagerInterface;

class CommandeClientHistoriqueController extends AbstractController
{
    #[Route('/commande/client/historique', name: 'app_commande_client_historique')]
  
    public function listeCommandes(EntityManagerInterface $entityManager): Response
    {
        // Utilisation de l'EntityManager pour récupérer toutes les commandes
        $commandes = $entityManager->getRepository(Commande::class)->findAll();

        // Afficher la vue avec les commandes récupérées
        return $this->render('commandeclient.html.twig', [
            'commandes' => $commandes,
        ]);

}
}
