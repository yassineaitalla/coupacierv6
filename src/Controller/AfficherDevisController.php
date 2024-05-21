<?php



namespace App\Controller;

use App\Entity\Devis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AfficherDevisController extends AbstractController
{
    #[Route('/devis', name: 'app_afficher_devis')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'ID du client depuis la session
        $clientId = $session->get('client_id');

        if ($clientId) {
            // Récupérer les devis du client connecté
            $devisRepository = $entityManager->getRepository(Devis::class);
            $devis = $devisRepository->findBy(['idclient' => $clientId]);

            // Si aucun devis n'est trouvé, définir un message
            if (!$devis) {
                $message = "Aucun devis pour l'instant.";
            } else {
                $message = null;
            }

            return $this->render('affichagedevis.html.twig', [
                'devis' => $devis,
                'message' => $message,
            ]);
        } else {
            // Rediriger ou afficher un message si l'utilisateur n'est pas connecté
            return $this->render('affichagedevis.html.twig', [
                'devis' => [],
                'message' => "Aucun devis pour l'instant.",
            ]);
        }
    }
}


    

   