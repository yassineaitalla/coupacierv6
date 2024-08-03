<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe route de l'attribut Route.

class BackofficeApprovisionnementController extends AbstractController
{
    #[Route('/backoffapro', name: 'backoffapprovisionnement')]
    public function backoff(SessionInterface $session): Response
    {
        return $this->render('backoffapro.html.twig', [
            'message' => 'Bienvenue sur la page Back office Approvisionnement !',
        ]);
    }

    #[Route('/deconnexionbackapro', name: 'deconnexionbackapro')]
    public function deconnexion(SessionInterface $session): Response
    {
        // Supprimer l'ID du client de la session
        $session->remove('EmployeEntreprise_id');

        // Rediriger l'utilisateur vers une page de confirmation de déconnexion ou toute autre page appropriéeee
        $this->addFlash('danger', 'Vous êtes deconnecté.');
        return $this->redirectToRoute('backoffapro');
    }
}
