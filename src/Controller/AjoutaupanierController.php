<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Entrepot;
use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AjoutaupanierController extends AbstractController
{
    private $entityManager;
    private $httpClient;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }

#[Route("/ajouter-au-panier/{id}", name: "ajouter_au_panier")]
public function ajouterAuPanier(Request $request, $id, SessionInterface $session, EntityManagerInterface $entityManager, ): RedirectResponse
{
    // Récupérer le produit à partir de son identifiant
    $produit = $entityManager->getRepository(Produit::class)->find($id);

    // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$produit) {
        
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer l'ID du client à partir de la session
    $clientId = $session->get('client_id');

    // Si l'ID du client n'est pas défini, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$clientId) {
       
    }

    // Récupérer le client à partir de son ID
    $client = $entityManager->getRepository(Client::class)->find($clientId);

    // Si le client n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$client) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer la quantité saisie par l'utilisateur
    $quantite = $request->request->get('quantite');
    $inp = $request->request->get('inp');
    $customMeasurement = $request->request->get('customMeasurement');

    // Vérifier si customMeasurement est défini et non vide avant de l'utiliser
    $mesure = null;
    if ($customMeasurement !== null && $customMeasurement !== '') {
        $mesure = (float) $customMeasurement;
    }


    
    // Vérifier si la quantité est définie et non vide
    if ($quantite !== null && $quantite !== '') {
        $quantite = intval($quantite); // Convertir en entier
    } else {
        // Si la quantité n'est pas définie ou vide, mettre la quantité par défaut à 1
        $quantite = 1;
    }

    $prixInitial = $produit->getPrix();
    $inp = floatval($inp);
    $customMeasurement= floatval($customMeasurement); // convertir en flottant
    $total = $customMeasurement  * $prixInitial * $quantite ;

    // Calculer le total en fonction de la longueur sélectionnée et du prix initial
    $masseLineaire = $produit->getMasseLineaireKgMetre();
    $coef = $produit->getCoef();

    $metre = 1;
  
    $prixDecoupe = $masseLineaire * $coef *  $quantite;

    // Vérifiez si le client est un ClientProfessionnel
if ($client && $client->getTypeClient() === 'ClientProfessionnel') {

    // Appliquer une remise de 10% au prix
    $prixInitial = $prixInitial * 0.90;
}
    
    $total = $customMeasurement  * $prixInitial * $quantite + $prixDecoupe;


    $poidsKg= $masseLineaire * $customMeasurement  * $quantite;

    // Calculer le poids total du panier en fonction de la masse linéaire
    $masseLineaire = $produit->getMasseLineaireKgMetre();
    $poidsKg = $masseLineaire * $customMeasurement  * $quantite;

   // Récupérer l'adresse du client
   $addressClient = $client->getAdresse() . ', ' . $client->getVille() . ', ' . $client->getCodePostal();

   // Récupérer tous les entrepôts depuis la base de données
   $entrepots = $entityManager->getRepository(Entrepot::class)->findAll();

   // Initialiser la distance minimale à une valeur élevée
   $minDistance = PHP_INT_MAX;
   $closestEntrepot = null;

   // Parcourir tous les entrepôts pour trouver le plus proche du client
     // Parcourir tous les entrepôts pour trouver le plus proche du client
     foreach ($entrepots as $entrepot) {
        // Récupérer l'adresse de l'entrepôt
        $addressEntrepot = $entrepot->getAdresseEntrepot() . ', ' . $entrepot->getVilleEntrepot() . ', ' . $entrepot->getCodePostal();
 
        // Récupérer les coordonnées géographiques des adresses
        $coordinatesClient = $this->getCoordinates($addressClient);
        $coordinatesEntrepot = $this->getCoordinates($addressEntrepot);
 
        if ($coordinatesClient && $coordinatesEntrepot) {
            // Calculer la distance entre le client et l'entrepôt
            $distanceInfo = $this->calculateDistanceBetweenPoints($coordinatesClient, $coordinatesEntrepot);
            $distance = $distanceInfo['value'] ?? null; // Utiliser la valeur par défaut null si 'value' n'existe pas dans le tableau
            $distanceUnit = $distanceInfo['unit'] ?? ''; // Utiliser une chaîne vide comme valeur par défaut pour 'unit'
 
            // Mettre à jour la distance minimale et l'entrepôt le plus proche si nécessaire
            if ($distance !== null && $distance < $minDistance) {
                $minDistance = $distance;
                $closestEntrepot = $entrepot;
            }
        }
    }
 
    // Si aucun entrepôt n'a été trouvé, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$closestEntrepot) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }
 
    // Calculer le prix de livraison en fonction de la distance et du poids
    $prixLivraison = $this->calculerPrixLivraison($minDistance, $poidsKg);
 
 

    // Créer une nouvelle instance de Panier
    $panier = new Panier();
    // Définir l'ID du produit dans le panier
    $panier->setIdProduit($produit);
    // Définir le total dans le panier
    $panier->setTotal($total);
    // Définir la quantité dans le panier
    $panier->setQuantite($quantite);
    // Définir la longueur dans le panier
    $panier->setLongueurMetre($customMeasurement );  // aaa revoir
    // Définir le client dans le panier
    $panier->setClient($client);
    // Définir le poids dans le panier
    $panier->setPoids($poidsKg);
    $panier->setPrixdecoupe(round($prixDecoupe, 2));
    $panier->setSurmesure($mesure);
    
    // Définir la distance dans le panier
    $panier->setDistance($minDistance . ' ' . $distanceUnit);
    $panier->setPrixLivraison(round($prixLivraison, 2)); // iruxlivraison * 20 % de TVA

    // Persister le panier 
    $entityManager->persist($panier);

    // Enregistrer les modifications dans la base de données
    $entityManager->flush();
    $this->addFlash('success', 'Votre produit à bien été ajouter au panier !');

    // Redirection vers la page des produits
    return $this->redirectToRoute('produits');
}

private function calculerPrixLivraison($distance, $poids) // on retourne le prix de la livraison + une tva de 20%
{
    $base = 40;
    $prix = $base + (0.3 * $distance);

    // Calcul de la majoration pour les tranches de 200 kg supplémentaires
    if ($poids > 200) {
        $tranchesSupplementaires = ceil(($poids - 200) / 200);
        $prix += $tranchesSupplementaires * 20;
    }

    // Calcul de la TVA à 20%
    $prixTTC = $prix * 1.20;

    return $prixTTC;
}

   

    private function getCoordinates(string $address): ?array
    {
        // Appeler l'API adresse pour géocoder l'adresse et obtenir les coordonnées (latitude et longitude)
        $response = $this->httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search/', [
            'query' => [
                'q' => $address,
                'limit' => 1
            ]
        ]);

        // Récupérer les données JSON de la réponse
        $data = $response->toArray();

        // Vérifier si des résultats ont été retournés
        if (!empty($data['features'])) {
            // Extraire les coordonnées (latitude et longitude) du premier résultat
            $coordinates = $data['features'][0]['geometry']['coordinates'];
            return ['latitude' => $coordinates[1], 'longitude' => $coordinates[0]];
        } else {
            return null; // En cas d'erreur ou d'absence de résultats
        }
    }

    private function calculateDistanceBetweenPoints(array $point1, array $point2): array
    {
        // Calculer la distance entre deux points en utilisant la formule Haversine
        $earthRadius = 6371; // Rayon moyen de la Terre en kilomètres
        $lat1 = deg2rad($point1['latitude']);
        $lon1 = deg2rad($point1['longitude']);
        $lat2 = deg2rad($point2['latitude']);
        $lon2 = deg2rad($point2['longitude']);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        if ($distance < 1) {
            $distanceMeters = round($distance * 1000); // Convertir en mètres et arrondir
            return ['value' => $distanceMeters, 'unit' => 'mètres'];
        } else {
            $distanceKilometers = round($distance, 2); // Arrondir à deux décimales
            return ['value' => $distanceKilometers, 'unit' => 'kilomètres'];
        }
    }
}
