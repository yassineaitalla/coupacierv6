<?php
// src/Controller/ImageController.php

// src/Controller/ImageController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AfficherimageController extends AbstractController
{
    private $entityManager;

    // Injection de l'EntityManager dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/images5', name: 'img')]
    public function images(): Response
    {
        // Construction de la requête avec le Query Builder de Doctrine
        $query = $this->entityManager->createQuery(
            'SELECT i.urlimage, p.id, p.nom, p.description, p.prix
            FROM App\Entity\Image i
            JOIN i.produit p'
        );

        // Exécution de la requête et récupération des résultats
        $images = $query->getResult();

        // Retourne la vue Twig avec les données
        return $this->render('images.html.twig', [
            'images' => $images,
        ]);
    }
}
