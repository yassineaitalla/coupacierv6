<?php



namespace App\Controller;

use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class StockController extends AbstractController
{
    #[Route('/stocks', name: 'liste_stocks')]
    public function listeStocks(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        // Vérifier si l'ID de l'employé est dans la session
        if ($session->get('EmployeEntreprise_id') === null) {
            // Rediriger vers la page backoff si l'employé n'est pas connecté
            return $this->render('backoff.html.twig', [
                'message' => 'Vous devez être connecté pour accéder à cette page.',
            ]);
        }
        // Récupérer tous les stocks
        $stocks = $entityManager->getRepository(Stock::class)->findAll();

        // Retourner la vue avec la liste des stocks
        return $this->render('stock.html.twig', [
            'stocks' => $stocks,
        ]);
    }
}

?>
