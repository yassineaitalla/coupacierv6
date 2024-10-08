<?php


namespace App\Controller;

use App\Entity\Devis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class AfficherDevisController extends AbstractController
{
    #[Route('/devis', name: 'app_afficher_devis')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'ID du client depuis la session
        $clientId = $session->get('client_id');
        // Vérifier si un client est connecté
        if (!$clientId) {
            // Rediriger l'utilisateur vers une page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Veuillez vous connecter pour accéder à votre liste de devis.');
            return $this->redirectToRoute('connexion'); // Redirection vers la page de connexion
        }

        if ($clientId) {
            // Récupérer les devis du client connecté
            $devisRepository = $entityManager->getRepository(Devis::class);
            $devis = $devisRepository->findBy(['idclient' => $clientId]);

            // Si aucun devis n'est trouvé, définir un message
            if (!$devis) {
                $message = "Aucun devis pour l'instant.";
            } else {
                $message = null;
            }

            return $this->render('affichagedevis.html.twig', [
                'devis' => $devis,
                'message' => $message,
            ]);
        } else {
            // Rediriger ou afficher un message si l'utilisateur n'est pas connecté
            return $this->render('affichagedevis.html.twig', [
                'devis' => [],
                'message' => "Aucun devis pour l'instant.",
            ]);
        }
    }

    #[Route('/devis/accepter/{id}', name: 'app_accepter_devis')]
    public function accepterDevis(int $id, EntityManagerInterface $entityManager): Response
    {
        $devis = $entityManager->getRepository(Devis::class)->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Le devis demandé n\'existe pas.');
        }

        // Mettre à jour le statut du devis
        $devis->setStatut('Accepté');
        $entityManager->persist($devis);
        $entityManager->flush();

        $this->addFlash('success', 'Le devis a été accepté veuillez vous présenter dans notre magasin pour procéder au paiement.');

        return $this->redirectToRoute('app_afficher_devis');
    }

    #[Route('/devis/refuser/{id}', name: 'app_refuser_devis')]
    public function refuserDevis(int $id, EntityManagerInterface $entityManager): Response
    {
        $devis = $entityManager->getRepository(Devis::class)->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Le devis demandé n\'existe pas.');
        }

        // Mettre à jour le statut du devis
        $devis->setStatut('Refusé');
        $entityManager->persist($devis);
        $entityManager->flush();

        $this->addFlash('success', 'Le devis a été refusé.');

        return $this->redirectToRoute('app_afficher_devis');
    }
}
