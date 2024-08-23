<?php

namespace App\Controller;

use App\Entity\Devis;
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

    
}
