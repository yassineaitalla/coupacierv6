<?php
namespace App\Controller;

use App\Entity\Devis;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class AfficherdevisclientController extends AbstractController
{
    private $devisRepository;

    public function __construct(DevisRepository $devisRepository)
    {
        $this->devisRepository = $devisRepository;
    }

    #[Route('/afficherdevisclient', name: 'app_afficherdevisclient')]
    public function devisclient(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $devis = $entityManager->getRepository(Devis::class)->findAll();

        // Afficher la vue avec les commandes récupérées
        return $this->render('devisclient.html.twig', [
            'devis' => $devis,
        ]);
    }
}
