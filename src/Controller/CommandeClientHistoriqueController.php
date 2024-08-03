<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;     // Importation des bibliothéques 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class CommandeClientHistoriqueController extends AbstractController
{
    #[Route('/commande/client/historique', name: 'app_commande_client_historique')]
  
    public function listeCommandes(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        // Vérifier si l'ID de l'employé est dans la session
        if ($session->get('EmployeEntreprise_id') === null) {
            // Rediriger vers la page backoff si l'employé n'est pas connecté
            return $this->render('backoff.html.twig', [
                'message' => 'Vous devez être connecté pour accéder à cette page.',
            ]);
        }
        // Utilisation de l'EntityManager pour récupérer toutes les commandes
        $commandes = $entityManager->getRepository(Commande::class)->findAll();

        // Afficher la vue avec les commandes récupérées
        return $this->render('commandeclient.html.twig', [
            'commandes' => $commandes,
        ]);

}
}
