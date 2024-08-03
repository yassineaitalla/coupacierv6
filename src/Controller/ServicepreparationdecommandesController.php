<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\EmployeEntreprise;

class ServicepreparationdecommandesController extends AbstractController
{
    #[Route('/servicepreparationdecommandes', name: 'app_servicepreparationdecommandes')]
    public function index(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $employeEntrepriseId = $session->get('EmployeEntreprise_id');
        if ($employeEntrepriseId !== null) {
            // Récupérer l'employé depuis la base de données
            $employeEntreprise = $entityManager->getRepository(EmployeEntreprise::class)->find($employeEntrepriseId);
            
            // Vérifier le rôle de l'employé
            $roles = explode(',', $employeEntreprise->getRoles());
            if ($employeEntreprise && in_array('Preparationdecommandes', $roles)) {
                // Rediriger vers la route app_servicepreparationdecommandespage
                return $this->redirectToRoute('app_servicepreparationdecommandespage');
            }
        }

        // Sinon, afficher la page backoff
        return $this->render('backoff.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/serviceprep', name: 'app_servicepreparationdecommandespage')]
    public function preparationdecommade(): Response
    {
        return $this->render('servicepreparationcommandes.html.twig', [
            'controller_name' => 'ServicepreparationdecommandesController',
        ]);
    }
}
