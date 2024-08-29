<?php


namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeF;
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
            return $this->redirectToRoute('connexion');
        }

        // Récupérer les résumés de commande (CommandeF) associés au client
        $commandeFs = $entityManager->getRepository(CommandeF::class)->findBy(['idclientt' => $clientId]);

        // Rendre la vue Twig avec les résumés de commande
        return $this->render('mes_commandes.html.twig', [
            'commandeFs' => $commandeFs,
        ]);
    }
    
    #[Route('/commande-details/{id}', name: 'commande_details')]
    public function afficherCommandeDetails(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les détails des commandes associées à un CommandeF spécifique
        $commandes = $entityManager->getRepository(Commande::class)->findBy(['commandeF' => $id]);

        if (!$commandes) {
            throw $this->createNotFoundException('Aucune commande trouvée pour ce résumé.');
        }

        return $this->render('commande_details.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
