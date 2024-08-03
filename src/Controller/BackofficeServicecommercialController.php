<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe route de l'attribut Route.

class BackofficeServicecommercialController extends AbstractController
{
    #[Route('/backoff', name: 'backoffservicecommercial')]
    public function backofficeservicecommercial(SessionInterface $session): Response
    {
        // Vérifier si l'ID de l'employé est dans la session
        if ($session->get('EmployeEntreprise_id') !== null) {
            // Rediriger vers la route app_devis
            return $this->redirectToRoute('app_affichage_deviscommercial');
        }

        // Sinon, afficher la page backoff
        return $this->render('backoff.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }
}
