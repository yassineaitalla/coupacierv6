<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ClientRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandeController extends AbstractController
{
    #[Route('/commande8', name: 'app_commande')]
    public function index(
        Request $request, 
        ClientRepository $clientRepository, 
        PanierRepository $panierRepository, 
        ProduitRepository $produitRepository, 
        EntityManagerInterface $entityManager, 
        SessionInterface $session
    ): Response {
        // Récupérer l'ID du client depuis la session
        $clientId = $session->get('client_id');

        // Vérifier si l'utilisateur est connecté
        if (!$clientId) {
            return $this->redirectToRoute('connexion');
        }

        // Récupérer l'entité Client à partir de l'ID
        $client = $clientRepository->find($clientId);

        // Récupérer les paniers du client
        $paniers = $panierRepository->findBy(['client' => $client]);

        // Si le formulaire est soumis en méthode POST
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $adresse = $request->request->get('adresse');
            $codePostal = $request->request->get('code_postal');
            $adresseFacturation = $request->request->get('adresse_facturation');
            $villeFacturation = $request->request->get('ville_facturation');
            $codePostalFacturation = $request->request->get('code_postal_facturation');
            $paysFacturation = $request->request->get('pays_facturation');

            // Traiter chaque panier pour créer une commande
            foreach ($paniers as $panier) {
                $commande = new Commande();
                $commande->setClient($client);
                $commande->setAdresseLivraison($adresse);
                $commande->setCodePostalLivraison($codePostal);
                $commande->setAdresseFacturation($adresseFacturation);
                $commande->setVilleFacturation($villeFacturation);
                $commande->setCodePostalFacturation($codePostalFacturation);
                $commande->setPaysFacturation($paysFacturation);
                $commande->setQuantite($panier->getQuantite());
                $commande->setTotalTtc($panier->getTotal());
                $commande->setMontantHorsTaxe(10); // Exemple de montant hors taxe fixé
                $commande->setEtat('en livraison');

                // Ajouter les produits associés au panier à la commande
                $produit = $panier->getIdProduit();
                $commande->addProduit($produit);

                $entityManager->persist($commande);
                $entityManager->remove($panier); // Supprimer le panier après création de la commande
            }

            // Enregistrer les commandes en base de données
            $entityManager->flush();

            // Rediriger vers la page de confirmation de commande
            $this->addFlash('success', 'Votre commande à bien était confirmer!');
            return $this->redirectToRoute('produits');
            
        }

        // Rendre le template 'commande.html.twig' avec les données nécessaires
        return $this->render('commande8.html.twig', [
            'client' => $client,
            'paniers' => $paniers,
        ]);
    }
}
