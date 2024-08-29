<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CommandeF;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandeClientHistoriqueController extends AbstractController
{
    #[Route('/commande/client/historique', name: 'app_commande_client_historique')]
    public function listeCommandes(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Vérifier si l'ID de l'employé est dans la session
        if ($session->get('EmployeEntreprise_id') === null) {
            return $this->render('backoff.html.twig', [
                'message' => 'Vous devez être connecté pour accéder à cette page.',
            ]);
        }

        // Récupérer toutes les commandes (CommandeF) via le repository
        $commandesF = $entityManager->getRepository(CommandeF::class)->findAll();

        // Afficher la vue des commandes
        return $this->render('commandeclient.html.twig', [
            'commandesF' => $commandesF,
        ]);
    }

    #[Route('/commande/details/{id}', name: 'commande_details')]
    public function detailsCommande(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les détails des commandes associées à un CommandeF spécifique
        $commandes = $entityManager->getRepository(Commande::class)->findBy(['commandeF' => $id]);

        if (!$commandes) {
            throw $this->createNotFoundException('Aucune commande trouvée pour ce résumé.');
        }

        return $this->render('detailscommande.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
