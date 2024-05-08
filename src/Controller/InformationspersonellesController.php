<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class InformationspersonellesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/informationspersonelles/{id}', name: 'app_informationspersonelles')]
    public function getClientInfo($id): Response
    {
        // Récupérer le client spécifique par son ID
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        // Vérifier si le client existe
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé.');
        }

        // Récupérer les informations spécifiques du client
        $nom = $client->getNom();
        $prenom = $client->getPrenom();
        $email = $client->getEmail();
        $telephone = $client->getTelephone();
        $motdepasse = $client->getMotdepasse();

        // Transmettre les informations du client au template Twig
        return $this->render('commande.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'motdepasse' => $motdepasse,
        ]);
    }
}
