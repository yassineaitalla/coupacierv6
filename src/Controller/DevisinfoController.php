<?php



namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DevisinfoController extends AbstractController
{
    #[Route('/devisinfo', name: 'devisinfo')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $devisRepository = $entityManager->getRepository(Devis::class);
        $devis = $devisRepository->findAll();

        // Récupérer l'ID du client depuis la session
        $clientId = $session->get('client_id');
        $afficherDevis = true;

        if ($clientId) {
            $clientRepository = $entityManager->getRepository(Client::class);
            $client = $clientRepository->find($clientId);

            if ($client && $client->getTypeClient() === 'Client Particulier') {
                $afficherDevis = false;
            }
        }

        return $this->render('informations.html.twig', [
            'devis' => $devis,
            'afficherDevis' => $afficherDevis,
        ]);
    }
}
