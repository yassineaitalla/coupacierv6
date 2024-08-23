<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseurs', name: 'liste_fournisseurs')]
    public function listeFournisseurs(EntityManagerInterface $entityManager , SessionInterface $session ): Response
    {
            // Vérifier si l'ID de l'employé est dans la session
            if ($session->get('EmployeEntreprise_id') === null) {
                // Rediriger vers la page backoff si l'employé n'est pas connecté
                return $this->render('backoff.html.twig', [
                    'message' => 'Vous devez être connecté pour accéder à cette page.',
                ]);
            }
        // Récupérer tous les fournisseurs
        $fournisseurs = $entityManager->getRepository(Fournisseur::class)->findAll();

        // Retourner la vue avec la liste des fournisseurs
        return $this->render('fournisseur.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }

    #[Route('/fournisseur/change-etat/{id}', name: 'change_etat_fournisseur')]
    public function changeEtatFournisseur(int $id, EntityManagerInterface $entityManager): Response
    {
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);

        if ($fournisseur) {
            // Changer l'état entre "En attente" et "Reçu"
            if ($fournisseur->getEtat() === 'En attente') {
                $fournisseur->setEtat('Reçu');
            } else {
                $fournisseur->setEtat('En attente');
            }

            // Sauvegarder les modifications
            $entityManager->flush();
        }

        // Rediriger vers la liste des fournisseurs après changement
        return $this->redirectToRoute('liste_fournisseurs');
    }
}
