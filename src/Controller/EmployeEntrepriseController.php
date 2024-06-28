<?php



namespace App\Controller;

use App\Entity\EmployeEntreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EmployeEntrepriseController extends AbstractController
{
    #[Route('/connexion-employe', name: 'connexion_employe')]
    public function seConnecter(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $motdepasse = $request->request->get('motdepasse');

            // Rechercher l'utilisateur dans la base de données par son email
            $EmployeEntreprise = $entityManager->getRepository(EmployeEntreprise::class)->findOneBy(['email' => $email]);

            // Vérifier si l'utilisateur existe
            if (!$EmployeEntreprise) {
                $this->addFlash('error', 'Adresse e-mail incorrecte.');
                return $this->redirectToRoute('connexion_employe');
            }

            // Vérifier si le mot de passe correspond
            if ($motdepasse !== $EmployeEntreprise->getMotdepasse()) {
                $this->addFlash('error', 'Mot de passe incorrect.');
                return $this->redirectToRoute('connexion_employe');
            }

            // Enregistrer l'ID du client dans la session
            $session->set('EmployeEntreprise_id', $EmployeEntreprise->getId());

            $this->addFlash('success', 'Vous êtes connecté.');
            return $this->redirectToRoute('app_affichage_deviscommercial');
        }

        // Si la méthode n'est pas POST, afficher la page de connexion
        return $this->render('backoff.html.twig');
    }


    #[Route('/deconnexionback', name: 'deconnexionback')]
    public function deconnexion(SessionInterface $session): Response
    {
        // Supprimer l'ID du client de la session
        $session->remove('EmployeEntreprise_id');

        // Rediriger l'utilisateur vers une page de confirmation de déconnexion ou toute autre page appropriéeee
        $this->addFlash('danger', 'Vous êtes deconnecté.');
        return $this->redirectToRoute('backoff');
    }


}
