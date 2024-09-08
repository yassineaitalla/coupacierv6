<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function seconnecter(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Nombre maximum de tentatives
        $maxAttempts = 5;
        $lockoutDuration = 15 * 60; // Verrouillage pendant 15 minutes (en secondes)

        // Vérifier si l'utilisateur est temporairement bloqué
        $lockoutTime = $session->get('lockout_time');
        if ($lockoutTime && time() < $lockoutTime) {
            $remainingTime = ($lockoutTime - time()) / 60;
            $this->addFlash('error', "Trop de tentatives échouées. Réessayez dans " . ceil($remainingTime) . " minutes.");
            return $this->redirectToRoute('pageconnexion');
        }

        // Si la requête est de type POST
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $motdepasse = $request->request->get('motdepasse');

            // Rechercher l'utilisateur par email
            $client = $entityManager->getRepository(Client::class)->findOneBy(['email' => $email]);

            // Vérifier si l'utilisateur existe
            if (!$client) {
                $this->addFlash('error', 'Adresse e-mail incorrecte.');
                return $this->incrementFailedAttempts($session);
            }

            // Vérifier le mot de passe
            if (!password_verify($motdepasse, $client->getMotdepasse())) {
                $this->addFlash('error', 'Mot de passe incorrect.');
                return $this->incrementFailedAttempts($session);
            }

            // Si connexion réussie, réinitialiser le compteur d'échecs
            $session->remove('failed_attempts');
            $session->remove('lockout_time');

            // Enregistrer l'ID du client dans la session
            $session->set('client_id', $client->getId());

            $this->addFlash('success', 'Vous êtes connecté.');
            return $this->redirectToRoute('produits');
        }

        // Si la méthode n'est pas POST, afficher la page de connexion
        return $this->render('connexion.html.twig');
    }

    // Fonction pour gérer les tentatives échouées
    private function incrementFailedAttempts(SessionInterface $session): Response
    {
        $failedAttempts = $session->get('failed_attempts', 0) + 1;
        $session->set('failed_attempts', $failedAttempts);

        // Vérifier si le nombre maximum de tentatives a été atteint
        if ($failedAttempts >= 5) {
            $session->set('lockout_time', time() + 15 * 60); // Bloquer pendant 15 minutes
            $this->addFlash('error', 'Trop de tentatives échouées. Vous êtes temporairement bloqué pendant 15 minutes.');
        }

        return $this->redirectToRoute('pageconnexion');
    }

    #[Route('/test5', name: 'verificationn')]
    public function verificationConnexion(SessionInterface $session): Response
    {
        $clientId = $session->get('client_id');

        if ($clientId) {
            return $this->redirectToRoute('recup_informations', ['id' => $clientId]);
        } else {
            return $this->redirectToRoute('pageconnexion');
        }
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(SessionInterface $session): Response
    {
        $session->remove('client_id');
        $this->addFlash('danger', 'Vous êtes déconnecté.');
        return $this->redirectToRoute('pageconnexion');
    }
}
