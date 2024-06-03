<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InformationspersonellesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
#[Route('/informations/{id}', name: 'recup_informations')]
public function getClientInfo($id, EntityManagerInterface $entityManager): Response
{
    // Récupérer le client spécifique par son ID
    $client = $entityManager->getRepository(Client::class)->find($id);

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
    $motdepasse = $client->getMotdepasse();

    $afficherDevis = $client && $client->gettypeclient() === 'ClientProfessionnel';

    // Transmettre les informations du client au template Twig
    return $this->render('informations.html.twig', [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'motdepasse' => $motdepasse,
        'afficherDevis' => $afficherDevis,
    ]);
}

}

