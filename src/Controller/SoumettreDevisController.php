<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Message;
use App\Entity\Client;
use App\Entity\Panier;
use App\Entity\Produit;

use App\Entity\DevisProduits;
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

    #[Route('/soumettre/devis', name: 'app_soumettre_devis', methods: ['GET', 'POST'])]
    public function soumettreDevis(Request $request, SessionInterface $session): Response
    {
        // Récupérer l'identifiant du client depuis la session
        $idClient = $session->get('client_id');

        // Récupérer le client à partir de son identifiant
        $client = $this->entityManager->getRepository(Client::class)->find($idClient);

        // Si le client n'existe pas, rediriger vers une page d'erreur
        if (!$client) {
            return $this->redirectToRoute('error_page'); // Remplacez par la route réelle vers la page d'erreur
        }

        // Créer une nouvelle instance de Devis
        $devis = new Devis();
        $devis->setIdclient($client);
        $devis->setStatut('En attente');

        // Récupérer les données du panier depuis la session
        $paniers = $this->entityManager->getRepository(Panier::class)->findBy(['client' => $client]);

        $totalPrix = 0; // Initialiser le total du prix

        foreach ($paniers as $panier) {
            $produit = $panier->getIdProduit();
            $quantite = $panier->getQuantite();
            $surMesure = $panier->getSurmesure();
            
            if ($produit && $quantite !== null) {
                // Créer une nouvelle instance de DevisProduit
                $devisProduit = new DevisProduits();
                $prixProduit = $produit->getPrix();
                $prixTotalLigne = $prixProduit * $quantite * ($surMesure ? 2 : 1); // Calcul du prix total
                
                $devisProduit->setProduit($produit);
                $devisProduit->setQuantite($quantite);
                $devisProduit->setPrixTotal($prixTotalLigne);
                $devisProduit->setSurMesure($surMesure);

                // Ajouter l'élément au devis
                $devis->addDevisProduit($devisProduit);
                
                $totalPrix += $prixTotalLigne;
            }
        }

        // Définir le prix total du devis
        //$devisProduit->setPrixTotal($totalPrix);

        // Persister le devis
        $this->entityManager->persist($devis);

        // Créer une nouvelle instance de Message
        $message = new Message();
        $messageClient = $request->request->get('messageClient');

        if (!empty($messageClient)) {
            $message->setMessageClient($messageClient);
            $message->setMessageVendeur('');
        }

        $message->setIdDevis($devis);
        $message->setIdClient($client);

        // Persister le message
        $this->entityManager->persist($message);

        // Enregistrer les changements dans la base de donnéess
        $this->entityManager->flush();

        // Vider le panier
        $panierRepository = $this->entityManager->getRepository(Panier::class);
        $panierRepository->deleteByClient($client);
        $session->remove('panier');

        $this->addFlash('success', 'Votre devis a bien été envoyé. Veuillez attendre la réponse du service commercial !');

        // Redirection vers une page de confirmation
        return $this->redirectToRoute('produits');
    }
}
