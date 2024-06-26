<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AfficherPageCommandeController extends AbstractController
{
    #[Route('/commanderclient', name: 'vacommandes')]
    public function listeCommandes(EntityManagerInterface $entityManager): Response
    {
        // Utilisation de l'EntityManager pour récupérer toutes les commandes
        $commandes = $entityManager->getRepository(Commande::class)->findAll();

        // Afficher la vue avec les commandes récupérées
        return $this->render('commandeclient.html.twig', [
            'commandes' => $commandes,
        ]);

}
}