<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModifierInformationClientController extends AbstractController
{
    #[Route('/modifier-informations-client', name: 'modifier_informations_client')]
    public function modifierInformationsclient(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'ID du client à partir de la session
        $clientId = $session->get('client_id');

        // Vérifier si l'ID du client est présent en session
        if (!$clientId) {
            // Gérer le cas où l'ID du client n'est pas en session
            throw new NotFoundHttpException('ID client non trouvé en session.');
        }

        // Récupérer l'entité Client à partir de l'ID
        $client = $entityManager->getRepository(Client::class)->find($clientId);

        // Vérifier si le client existe en base de données
        if (!$client) {
            // Gérer le cas où le client n'existe pas en base de données
            throw new NotFoundHttpException('Client non trouvé en base de données.');
        }

        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $telephone = $request->request->get('telephone');
            $motdepasse = $request->request->get('motdepasse');
            // Vous pouvez ajouter d'autres champs ici

            // Vérification basique des champs
            if (empty($nom) || empty($prenom) || empty($email) || empty($telephone)) {
                $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
                return $this->redirectToRoute('modifier_informations_client');
            }

            // Mettre à jour les données du client
            $client->setNom($nom);
            $client->setPrenom($prenom);
            $client->setEmail($email);
            $client->setTelephone($telephone);

            // Vérifier si un nouveau mot de passe est fourni
            if (!empty($motdepasse)) {
                // Hashage du nouveau mot de passe
                $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
                $client->setMotdepasse($hashedPassword);
            }

            // Persister les changements en base de données
            $entityManager->flush();

            // Ajouter un message flash pour indiquer le succès de la modification
            $this->addFlash('success', 'Les informations du client ont été mises à jour avec succès.');

            // Redirection vers une autre page après la modification
            return $this->redirectToRoute('accueil'); // Remplacez 'accueil' par la route de votre choix
        }

        // Si la méthode est GET, rendre le formulaire avec les données actuelles du client
        return $this->render('informations.html.twig', [
            'client' => $client,
        ]);
    }
}
