<?php



namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeF;
use App\Entity\BordereauLivraison;
use App\Repository\ClientRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
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
        ProduitRepository $produitRepository, 
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
            $adresse = $request->request->get('adresse');
            $codePostal = $request->request->get('code_postal');
            $adresseFacturation = $request->request->get('adresse_facturation');
            $villeFacturation = $request->request->get('ville_facturation');
            $codePostalFacturation = $request->request->get('code_postal_facturation');
            $paysFacturation = $request->request->get('pays_facturation');

            $totalPanier = 0;
            $totalLivraison= 0;
            $totalDecoupe=0;

            // Créer une seule entrée dans CommandeF avec le total des produits
            $commandeF = new CommandeF();
            $commandeF->setTotal($totalPanier);
            $commandeF->setIdclientt($client);
            $commandeF->setEtat('En preparation');
            $entityManager->persist($commandeF);
            $entityManager->flush(); // Pour obtenir l'ID de CommandeF

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
                $commande->setPrix($panier->getTotal());
                $commande->setMontantHorsTaxe(10); // Exemple de montant hors taxe fixé
                

                $produit = $panier->getIdProduit();
                $commande->setProduit($produit);

                // Associer la commande à CommandeF
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
