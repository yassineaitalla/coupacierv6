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
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    #[Route('/accueil', name: 'page_accueil')]
    public function accueil(Request $request): Response
    {
        // Récupérer le chemin de la page visitée
        $pageVisitee = $request->getPathInfo();

        // Créer une nouvelle instance de Visiteursite
        $visiteur = new Visiteursite();
        $visiteur->setPageVisiter($pageVisitee);

        // Utiliser le DocumentManager pour persist et flush
        $this->documentManager->persist($visiteur);
        $this->documentManager->flush();

        // Récupérer les visiteurs enregistrés pour les afficher
        $visiteurs = $this->documentManager->getRepository(Visiteursite::class)->findAll();

        // Rendu de la page accueil.html.twig avec un message
        return $this->render('accueil.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
            'visiteurs' => $visiteurs, // Passer les données au template
        ]);
    }
}
