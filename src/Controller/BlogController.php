<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Listedenvies;
use App\Entity\Produit;
use App\Entity\Test;
use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.


class BlogController extends AbstractController
{
        
    public function __construct( EntityManagerInterface $entityManager)
    {
      
     
        $this->entityManager = $entityManager;
        
    }
    

    #[Route('/formpart', name: 'formpart')]
    public function formpart(): Response
    {
        return $this->render('formpart.html.twig', [
            'message' => 'Bienvenue sur la page formpart !',
        ]);
    }

    #[Route('/quisommesnous', name: 'quisommesnous')]
    public function quisommesnous(): Response
    {
        return $this->render('quisommesnous.html.twig', [
            'message' => 'Bienvenue sur la page qui sommes nous !',
        ]);
    }

        
    #[Route('/informations', name: 'informations')]
    public function pageinformations(): Response
    {
        return $this->render('informations.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/motdepasseoublie', name: 'motdepasseoublie')]
    public function motdepasseoublie(): Response
    {
        return $this->render('motdepasseoublie.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('test.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    
    #[Route('/compte', name: 'compte')]
    public function pagecompte(): Response
    {
        return $this->render('compte.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/commandepage', name: 'commandepage')]
    public function afficherpagecommande(): Response
    {
        return $this->render('pagecommande.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }



    #[Route('/pageconnexion', name: 'pageconnexion')]
    public function Connexion(): Response
    {
        return $this->render('connexion.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    

    #[Route('/commandE8', name: 'vacommandes')]
    public function Commande(): Response
    {
        return $this->render('commande8.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }


    
    #[Route('/afficherdeviss', name: 'afficherdeviss')]
    public function afficherDevisc(SessionInterface $session, ClientRepository $clientRepository): Response
    {
        // Récupérer l'ID du client depuis la session
        $clientId = $session->get('client_id');
        $client = $clientRepository->find($clientId);

        // Vérifier si le client est un professionnel
        $afficherDevisClient = $client && $client->getTypeClient() === 'ClientProfessionnel';

        return $this->render('navbar.html.twig', [
            'afficherDevisclient' => $afficherDevisClient,
        ]);
    }
    




    #[Route('/listedenvies', name: 'listedenvies')]
    public function listedEnvies(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'ID du client connecté depuis la session
        $clientId = $session->get('client_id');
    
        // Vérifier si un client est connecté
        if (!$clientId) {
            // Rediriger l'utilisateur vers une page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Veuillez vous connecter pour accéder à votre liste d\'envies.');
            return $this->redirectToRoute('connexion'); // Redirection vers la page de connexion
        }
    
        // Récupérer les produits de la liste d'envies du client connecté depuis la base de données
        $listedEnvies = $entityManager->getRepository(Listedenvies::class)->findBy(['client' => $clientId]);
    
        $sommeTotal = 0;
        foreach ($listedEnvies as $produit) {
            $sommeTotal += $produit->getTotal();
        }
    
        return $this->render('listedenvies.html.twig', [
            'listedEnvies' => $listedEnvies,
            'isEmpty' => empty($listedEnvies), // Ajout de cette variable pour vérifier si la liste est vide
            'sommeTotal' => $sommeTotal,
        ]);
    }
    





#[Route('/ajouter-au-panierrr/{id}', name: 'ajouter')]
public function ajouterAuPanier(Request $request, $id, SessionInterface $session): Response
{
    // Récupérer le produit à partir de son identifiant
    $produit = $this->entityManager->getRepository(Produit::class)->find($id);

    // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$produit) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer l'ID du client à partir de la session
    $clientId = $session->get('client_id');

    // Si l'ID du client n'est pas défini, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$clientId) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer le client à partir de son ID
    $client = $this->entityManager->getRepository(Client::class)->find($clientId);

    // Si le client n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$client) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Vérifier si le produit existe déjà dans le panier pour cet utilisateur
    $panierExistant = $this->entityManager->getRepository(Panier::class)->findOneBy(['client' => $client, 'id_produit' => $produit]);

    // Récupérer la quantité saisie par l'utilisateur
    $quantite = $request->request->get('quantite');

    // Vérifier si la quantité est définie et non vide
    if ($quantite !== null && $quantite !== '') {
        $quantite = intval($quantite); // Convertir en entier
    } else {
        // Si la quantité n'est pas définie ou vide, mettre la quantité par défaut à 1
        $quantite = 1;
    }

    // Calculer le total en multipliant la quantité par le prix du produit
    $total = $quantite * $produit->getPrix();

    // Si le produit n'est pas déjà dans le panier, l'ajouter
    if (!$panierExistant) {
        // Créer une nouvelle instance de Panier
        $panier = new Panier();
        // Définir l'ID du produit dans le panier
        $panier->setIdProduit($produit);
        // Définir le total dans le panier
        $panier->setTotal($total);
        // Définir la quantité dans le panier
        $panier->setQuantite($quantite);
        // Définir le client dans le panier
        $panier->setClient($client);
        // Persister le panier
        $this->entityManager->persist($panier);
    } else {
        // Si le produit est déjà dans le panier, modifier la quantité et le total
        $panierExistant->setQuantite($quantite);
        $panierExistant->setTotal($total);
    }

    // Enregistrer les modifications dans la base de données
    $this->entityManager->flush();

    // Supprimer l'entrée correspondante de la liste d'envies
    $listedEnvie = $this->entityManager->getRepository(Listedenvies::class)->findOneBy(['client' => $client, 'idproduit' => $produit]);
    if ($listedEnvie) {
        $this->entityManager->remove($listedEnvie);
        $this->entityManager->flush();
    }

    // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
    return $this->redirectToRoute('produits');
}




    private $entityManager;
    private $panierService;

    

    #[Route('/produits', name: 'produits')]
    public function index(Request $request): Response
    {
        $produits = $this->entityManager->getRepository(  Produit::class)->findAll();

        $clientId = $request->getSession()->get('client_id');
        
        if (!$clientId) {
            // Rediriger vers la page de connexion si le client n'est pas connecté
            return $this->redirectToRoute('connexion'); // Assurez-vous que cette route existe
        }
        

        return $this->render('produits.html.twig', [
            'produits' => $produits,

        
        ]);
    }


    #[Route('/produits', name: 'produits')]
    public function Produits(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'identifiant du client depuis la session
        $clientId = $request->getSession()->get('client_id');

        // Vérifier si un client est trouvé avec cet identifiant
        if ($clientId) {
            // Récupérer le client depuis la base de données en utilisant l'identifiant
            $client = $entityManager->getRepository(Client::class)->find($clientId);

            // Vérifier si le client est un ClientProfessionnel
            $remise = $client && $client->getTypeClient() === 'ClientProfessionnel';
        } else {
            // Si aucun client n'est trouvé dans la session, ne pas appliquer de remise
            $remise = false;
        }

        // Récupérer tous les produits
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        // Appliquer la remise de 10% aux prix des produits si le client est professionnel
        if ($remise) {
            foreach ($produits as $produit) {
                $nouveauPrix = $produit->getPrix() * 0.90; // Appliquer la réduction de 10%
                $produit->setPrix($nouveauPrix);
            }
        }

        // Retourner la vue avec les produits et la variable remise
        return $this->render('produits.html.twig', [
            'produits' => $produits,
            'remise' => $remise,
        ]);
    }




    #[Route('/supprimer-produit-liste-envies/{id}', name: 'supprimer_produit_liste_envies')]
    public function supprimerProduitListeEnvies($id, SessionInterface $session): Response
    {
        // Récupérer le produit à partir de son identifiant
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
    
        // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
        if (!$produit) {
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }
    
        // Récupérer l'ID du client à partir de la session
        $clientId = $session->get('client_id');
    
        // Si l'ID du client n'est pas défini, rediriger vers une page d'erreur ou afficher un message d'erreur
        if (!$clientId) {
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }
    
        // Récupérer l'entrée de la liste d'envies correspondant au produit et au client
        $listedEnvie = $this->entityManager->getRepository(Listedenvies::class)->findOneBy(['client' => $clientId, 'idproduit' => $produit]);
    
        // Si l'entrée de la liste d'envies existe, supprimer l'entrée
        if ($listedEnvie) {
            $this->entityManager->remove($listedEnvie);
            $this->entityManager->flush();
        }
        $this->addFlash('success', 'Votre produit à bien été supprimer de la liste denvies!');
    
        // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
        return $this->redirectToRoute('listedenvies'); // Vous pouvez remplacer 'produits' par la route de votre choix
    }
    
    

     #[Route('/image', name: 'image')]
     public function ajouterImage(Request $request): Response
     {
         if ($request->isMethod('POST')) {
             // Créer une nouvelle instance de l'entité Test
             $produit = new Produit();
 
             // Récupérer le fichier image envoyé depuis le formulaire
             $imageFile = $request->files->get('image');
 
             if ($imageFile) {
                 // Générer un nom de fichier unique
                 $nomFichier = uniqid().'.'.$imageFile->getClientOriginalExtension();
 
                 // Déplacer le fichier dans le répertoire où sont stockées les images
                 try {
                     $imageFile->move(
                         $this->getParameter('dossier_images'),
                         $nomFichier
                     );
                 } catch (FileException $e) {
                     // Gérer l'exception si le fichier ne peut pas être déplacé
                 }
 
                 // Mettre à jour la propriété 'image' de l'entité Test avec le nom du fichier
                 $produit->setImage($nomFichier);
 
                 // Persister l'entité dans la base de données
                 $this->entityManager->persist($produit);
                 $this->entityManager->flush();
 
                 // Rediriger vers une page de succès ou une autre action
                 return $this->redirectToRoute('image');
             }
         }
 
         // Afficher le formulaire
         return $this->render('image.html.twig');
     }



    #[Route('/image1', name: 'image1')]
    public function afficherImage(): Response
     {
         // Récupérer l'image depuis l'entité Test
         $test = $this->entityManager->getRepository(Test::class)->findOneBy([]);
 
         // Récupérer le nom du fichier de l'image
         $nomImage = $test ? $test->getImage() : null;
 
         // Afficher le template avec le nom du fichier de l'image
         return $this->render('image1.html.twig', [
             'nomImage' => $nomImage,
         ]);
     }
     
     


    #[Route('/demanderdevis', name: 'demanderdevis')]
    public function demanderDevis(): Response
    {
        return $this->render('demanderdevis.html.twig');
    }






#[Route('/informations/{id}', name: 'recup_informations')]
public function getClientInfo($id, EntityManagerInterface $entityManager): Response
{
    // Récupérer le client spécifique par son ID
    $client = $entityManager->getRepository(Client::class)->find($id);

    // Vérifier si le client existe
    if (!$client) {
        throw $this->createNotFoundException('Client non trouvé.');
    }

    // Récupérer les informations spécifiques du client
    $nom = $client->getNom();
    $prenom = $client->getPrenom();
    $email = $client->getEmail();
    $telephone = $client->getTelephone();
    $motdepasse = $client->getMotdepasse();
    $motdepasse = $client->getMotdepasse();

    $afficherDevis = $client && $client->gettypeclient() === 'ClientProfessionnel';

    // Transmettre les informations du client au template Twig
    return $this->render('informations.html.twig', [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'motdepasse' => $motdepasse,
        'afficherDevis' => $afficherDevis,
    ]);
}


}











   
    

    

    

   

    
    