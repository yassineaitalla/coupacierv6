<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Message;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SoumettreDevisController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/soumettre/devis', name: 'app_soumettre_devis', methods: ['GET','POST'])]
    public function soumettreDevis(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'identifiant du client depuis la session
        $idClient = $session->get('client_id');

        // Récupérer le client à partir de son identifiant
        $client = $entityManager->getRepository(Client::class)->find($idClient);

        // Si le client n'existe pas, rediriger vers une page d'erreur
        if (!$client) {
            // Gérer le cas où le client n'existe pas
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }

        // Créer une nouvelle instance de Devis
        $devis = new Devis();

        // Définir le panier et le client dans le devis
        $devis->setIdclient($client);

        // Définir le statut du devis
        $devis->setStatut('En attente');

        // Persister le devis
        $entityManager->persist($devis);

        // Créer une nouvelle instance de Message
        $message = new Message();

        // Récupérer le message client depuis les données du formulaire POST
        $messageClient = $request->request->get('messageClient');

        // Vérifier si le message client n'est pas vide
        if (!empty($messageClient)) {
            // Définir le message client et le message vendeur
            $message->setMessageClient($messageClient);
            $message->setMessageVendeur('');
        }

        // Associer le message au devis et au client
        $message->setIdDevis($devis);
        $message->setIdClient($client);

        // Persister le message
        $entityManager->persist($message);

        // Enregistrer les changements dans la base de données
        $entityManager->flush();
        $this->addFlash('success', 'Votre Mesage à bien était envoyé, Veuillez attendre la réponse du service commercial !');

        // Redirection vers une page de confirmation
        return $this->redirectToRoute('app_soumettre_devis');
    }

    
}


