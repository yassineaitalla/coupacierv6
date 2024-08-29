<?php


namespace App\Controller;

use App\Document\Visiteursite;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Utilisation de l'annotation Route

class AccueilController extends AbstractController
{
    private DocumentManager $documentManager; //  DocumentManager $documentManager c'est gestionnaire de documents injecté pour effectuer des opérations sur la base de données MongoDB.

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    #[Route('/visite', name: 'visite')]
    public function accueil(Request $request): Response
    {
        // Récupérer le chemin de la page visitée
        $pageVisitee = $request->getPathInfo();

        // créer une nouvelle instance de Visiteursite
        $visiteur = new Visiteursite();
        $visiteur->setPageVisiter($pageVisitee);

        // uiliser le documentManager pour persist 
        $this->documentManager->persist($visiteur);
        $this->documentManager->flush();

        // retourne une réponse vide (statut 204 No Content)
        return new Response(null, Response::HTTP_NO_CONTENT);
      
    }

    #[Route('/accueil', name: 'page_accueil')]
    public function quisommesnous(): Response
    {
        return $this->render('accueil.html.twig', [
            'message' => 'Bienvenue sur la page qui sommes nous !',
        ]);
    }
}
