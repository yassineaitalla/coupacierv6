<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\DevisProduits;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AffichageDeviscommercialController extends AbstractController
{
    #[Route('/deviscommercial', name: 'app_affichage_deviscommercial')]
    public function index(SessionInterface $session, EntityManagerInterface $em): Response
    {
        // Vérifier si l'ID de l'employé est dans la session
        if ($session->get('EmployeEntreprise_id') === null) {
            // Rediriger vers la page backoff si l'employé n'est pas connecté
            return $this->render('backoff.html.twig', [
                'message' => 'Vous devez être connecté pour accéder à cette page.',
            ]);
        }

        // Récupérer tous les devis
        $devis = $em->getRepository(Devis::class)->findAll();

        // Récupérer tous les messages
        $messages = $em->getRepository(Message::class)->findAll();

        return $this->render('deviscommercial.html.twig', [
            'controller_name' => 'AffichageDeviscommercialController',
            'devis' => $devis,
            'messages' => $messages,
        ]);
    }

    #[Route('/update_status/{id}', name: 'update_status')]
    public function updateStatus(Request $request, EntityManagerInterface $em, $id): Response
    {
        // Récupérer l'ID du devis à mettre à jour depuis l'URL
        $devisId = $id;  // Utiliser l'ID passé dans l'URL

        // Récupérer le devis à partir de son ID
        $devis = $em->getRepository(Devis::class)->find($devisId);

        // Vérifier si le devis existe
        if (!$devis) {
            // Rediriger ou afficher un message d'erreur si le devis n'est pas trouvé
            return new Response("Devis not found", Response::HTTP_NOT_FOUND);
        }

        // Récupérer le nouveau statut à partir de la requête
        $newStatus = $request->request->get('newStatus');

        // Mettre à jour le statut du devis
        $devis->setStatut($newStatus);

        // Enregistrer les modifications dans la base de données
        $em->flush();

        // Rediriger vers la page d'affichage des devis après la mise à jour du statut
        return $this->redirectToRoute('app_affichage_deviscommercial');
    }

    #[Route('/detailsmessage/{id}', name: 'details_message')]
    public function detailsMessage($id, EntityManagerInterface $em): Response
    {
        // Récupérer le devis correspondant à l'ID
        $devis = $em->getRepository(Devis::class)->find($id);

        // Récupérer le message associé au devis
        $message = $em->getRepository(Message::class)->findOneBy(['idDevis' => $id]);

        if (!$message) {
            return new Response("Message not found", Response::HTTP_NOT_FOUND);
        }

        return $this->render('detailsmessage.html.twig', [
            'devis' => $devis,
            'message' => $message,
        ]);
    }

    #[Route('/details_devis/{id}', name: 'details_devis')]
    public function detailsDevis(EntityManagerInterface $em, int $id): Response
    {
        // Récupérer les produits associés au devis
        $devisProduits = $em->getRepository(DevisProduits::class)->findBy(['devis' => $id]);

        // Vérifier si le devis contient des produits
        if (!$devisProduits) {
            throw $this->createNotFoundException('Aucun produit trouvé pour ce devis.');
        }

        return $this->render('detailsdevisclient.html.twig', [
            'devisProduits' => $devisProduits,
        ]);
    }

    #[Route('/update_prix_total', name: 'update_prix_total', methods: ['POST'])]
    public function updatePrixTotal(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'ID du produit et le nouveau prix total depuis la requête
        $devisProduitId = $request->request->get('devisProduitId');
        $nouveauPrixTotal = $request->request->get('prixTotal');

        if (!$devisProduitId || !$nouveauPrixTotal) {
            // Retourner une réponse d'erreur si les données sont manquantes
            return new Response("Données manquantes", Response::HTTP_BAD_REQUEST);
        }

        // Trouver l'entité DevisProduits par son ID
        $devisProduit = $em->getRepository(DevisProduits::class)->find($devisProduitId);

        if (!$devisProduit) {
            // Retourner une réponse d'erreur si l'entité n'est pas trouvée
            return new Response("Produit non trouvé", Response::HTTP_NOT_FOUND);
        }

        // Mettre à jour le prix total
        $devisProduit->setPrixTotal((float) $nouveauPrixTotal);
        $em->persist($devisProduit);
        $em->flush();

        // Rediriger vers les détails du devis après la mise à jour
        return $this->redirectToRoute('details_devis', ['id' => $devisProduit->getDevis()->getId()]);
    }

    #[Route('/update_message_vendeur/{id}', name: 'update_message_vendeur', methods: ['POST'])]
public function updateMessageVendeur(Request $request, EntityManagerInterface $em, int $id): Response
{
    // Récupérer le message à mettre à jour
    $message = $em->getRepository(Message::class)->find($id);

    // Vérifier si le message existe
    if (!$message) {
        // Ajouter un message flash d'erreur
        $this->addFlash('error', 'Message non trouvé.');

        // Rediriger vers la liste des devis ou une autre page appropriée
        return $this->redirectToRoute('app_affichage_deviscommercial');
    }

    // Récupérer la réponse du vendeur depuis la requête
    $messageVendeur = $request->request->get('messageVendeur');

    // Mettre à jour le message du vendeur
    $message->setMessageVendeur($messageVendeur);

    // Enregistrer les modifications dans la base de données
    $em->flush();

    // Ajouter un message flash de succès
    $this->addFlash('success', 'Votre réponse a été envoyée avec succès.');

    // Rediriger vers la page des détails du devis en passant l'ID du devis
    return $this->redirectToRoute('details_message', ['id' => $message->getIdDevis()->getId()]);
}

    
}
