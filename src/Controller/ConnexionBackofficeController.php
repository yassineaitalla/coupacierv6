<?php

// Je définis le namespace (espace de noms) pour mon contrôleur
namespace App\Controller;

// J'importe les classes nécessaires depuis Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Je crée une nouvelle classe nommée ConnexionBackofficeController qui hérite d'AbstractController
class ConnexionBackofficeController extends AbstractController
{
    // Je déclare une nouvelle route pour l'URL /connexion/backoffice
    // Quand quelqu'un visite cette URL, la méthode index() sera appelée
    #[Route('/connexion/backoffice', name: 'app_connexion_backoffice')]
    public function index(): Response
    {
        // La méthode index() renvoie une réponse en affichant le template pageconnexionbackoffice.html.twig
        return $this->render('pageconnexionservicebackoffice.html.twig', [
            // Ici, je pourrais passer des variables au template, mais je laisse ça vide pour l'instant
        ]);
    }
}
