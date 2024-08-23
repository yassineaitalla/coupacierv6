<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfficherCommandeController extends AbstractController
{
    #[Route('/mes-commandes', name: 'mes_commandes')]
    public function afficherCommandes(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'ID du client depuis la session
        $clientId = $request->getSession()->get('client_id');

        // Vérifier si le client est connecté
        if (!$clientId) {
            // Rediriger vers la page de connexion si le client n'est pas connecté
            return $this->redirectToRoute('connexion');
        }

        // Récupérer les commandes du client depuis la base de données avec l'EntityManager
        $commandes = $entityManager->getRepository(Commande::class)->findBy(['client' => $clientId]);

        // Rendre la vue Twig avec les commandes du client
        return $this->render('mes_commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
