<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeF;
use App\Entity\Facture;
use App\Entity\BordereauLivraison;
use App\Repository\ClientRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CommandeController extends AbstractController
{
    #[Route('/commande8', name: 'app_commande')]
    public function index(
        Request $request, 
        ClientRepository $clientRepository, 
        PanierRepository $panierRepository, 
        EntityManagerInterface $entityManager, 
        SessionInterface $session
    ): Response {
        $clientId = $session->get('client_id');
        if (!$clientId) {
            return $this->redirectToRoute('connexion');
        }

        $client = $clientRepository->find($clientId);
        $paniers = $panierRepository->findBy(['client' => $client]);

            if ($request->isMethod('POST')) {
                $adresselivraison = $request->request->get('adresse_livraison');
                $codepostallivraison = $request->request->get('code_postal_livraison');
                $villelivraison=  $request->request->get('ville_livraison');
                $paysLivraison = $request->request->get('pays_livraison');

                $adresseFacturation = $request->request->get('adresse_facturation');
                $villeFacturation = $request->request->get('ville_facturation');
                $codePostalFacturation = $request->request->get('code_postal_facturation');
                $paysFacturation = $request->request->get('pays_facturation');

            $totalPanier = 0;
            $totalLivraison = 0;
            $totalDecoupe = 0;

            // Créer une seule entrée dans CommandeF avec le total des produits
            $commandeF = new CommandeF();
            $commandeF->setTotal(0); // Initial total
            $commandeF->setIdclientt($client);
            $commandeF->setEtat('En préparation');
            $entityManager->persist($commandeF);
            $entityManager->flush(); // Pour obtenir l'ID de CommandeF

            foreach ($paniers as $panier) {
                $commande = new Commande();
                $commande->setClient($client);
                $commande->setQuantite($panier->getQuantite());
                $commande->setPrix($panier->getTotal());
                $commande->setMontantHorsTaxe(10); // Exemple de montant hors taxe fixé
                $commande->setProduit($panier->getIdProduit());
                $commande->setSurmesure($panier->getSurmesure());

                $commande->setCommandeF($commandeF);
                
                $entityManager->persist($commande);

                // Accumuler le total du panier
                $totalPanier += $panier->getTotal();

                // Créer le bordereau de livraison
                $bordereau = new BordereauLivraison();
                $reference = uniqid('ref_'); // Génération d'une référence unique
                $bordereau->setReference($reference);
                $bordereau->setIdcommande($commande);

                // Génération du QR code
                $qrCode = new QrCode($reference);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                $qrCodeData = $result->getDataUri();
                $bordereau->setQrcode($qrCodeData);

                $bordereau->setDateCreation(new \DateTime());

                $entityManager->persist($bordereau);

                $entityManager->remove($panier);

                // Accumuler le total du panier, livraison et découpe
                $totalPanier += $panier->getTotal();
                $totalLivraison += $panier->getPrixLivraison();
                $totalDecoupe += $panier->getPrixDecoupe();
            }

            // Créer une facture associée à la commandeF
            $facture = new Facture();
            $facture->setAdresseLivraison($adresselivraison);
            $facture->setCodePostalLivraison($codepostallivraison);
            $facture->setPaysLivraison($paysLivraison);
            $facture->setVilleLivraison($villelivraison);

            $facture->setAdresseFacturation($adresseFacturation);
            $facture->setVilleFacturation($villeFacturation);
            $facture->setCodePostalFacturation($codePostalFacturation);
            $facture->setPaysFacturation($paysFacturation);
           

            $facture->setCommandeF($commandeF);

            $entityManager->persist($facture);

            // Mettre à jour le total dans CommandeF
            $commandeF->setTotal($totalPanier + $totalDecoupe + $totalLivraison);
            $entityManager->persist($commandeF);

            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a bien été confirmée!');
            return $this->redirectToRoute('produits');
        }

        return $this->render('commande8.html.twig', [
            'client' => $client,
            'paniers' => $paniers,
        ]);
    }
}
