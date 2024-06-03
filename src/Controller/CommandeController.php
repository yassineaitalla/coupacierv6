<?php

namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Commande;
use App\Entity\Client;


class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Supposons que l'ID du client soit stocké dans la session avec la clé 'client_id'
        $clientId = $request->getSession()->get('client_id');

       

        // Récupérer les paniers pour ce client
        $paniers = $entityManager->getRepository(Panier::class)->findBy(['client' => $clientId]);

        $totalGeneral = 0;
        $prixLivraison = 0;
        $prixTtc= 0;


        foreach ($paniers as $panier) {
            $totalGeneral += $panier->getTotal();  // Assuming getTotal() returns the total for the panier
            $prixLivraison = $panier->getPrixLivraison(); 
            $prixTtc = $panier->getTotal() + $panier->getPrixLivraison(); // Assuming all rows have the same delivery price
        }

        return $this->render('commande.html.twig', [
            'paniers' => $paniers,
            'totalGeneral' => $totalGeneral,
            'prixLivraison' => $prixLivraison,
            'prixTtc' => $prixTtc
        ]);
    }

    // src/Controller/CommandeController.php





    #[Route('/commande/ajouter', name: 'ajouter_commande', methods: ['POST'])]
    public function ajouterCommande(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer les données du formulaire de paiement
        $adresse = $request->request->get('adresse');
        $ville = $request->request->get('ville');
        $codePostal = $request->request->get('code_postal');
        $pays = 'France'; // Par défaut, vous pouvez ajuster cela

        // Récupérer l'ID du client depuis la session
        $clientId = $session->get('client_id');

        // Récupérer le client depuis la base de données
        $client = $entityManager->getRepository(Client::class)->find($clientId);

        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

        // Créer une nouvelle commande
        $commande = new Commande();
        $commande->setClient($client);
        $commande->setAdresseFacturation($adresse);
        $commande->setVilleFacturation($ville);
        $commande->setCodePostalFacturation($codePostal);
        $commande->setPaysFacturation($pays);
        $commande->setIdproduit(2);
        $commande->setQuantite(20);
        $commande->setMontantHorsTaxe(200);
        $commande->setTotalTtc(100);

        // Persister la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        // Rediriger ou renvoyer une réponse appropriée
        return $this->redirectToRoute('some_route'); // Remplacez 'some_route' par la route souhaitée après paiement
    }
}

