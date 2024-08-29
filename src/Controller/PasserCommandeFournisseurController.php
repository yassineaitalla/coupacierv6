<?php



namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\EmployeEntreprise;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PasserCommandeFournisseurController extends AbstractController
{
    #[Route('/ajouter/fournisseur', name: 'ajouter_fournisseur')]
    public function ajouterFournisseur(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'ID de l'employé connecté depuis la session
        $employeEntrepriseId = $session->get('EmployeEntreprise_id');
        
        if ($employeEntrepriseId !== null) {
            // Récupérer l'employé depuis la base de données
            $employeEntreprise = $entityManager->getRepository(EmployeEntreprise::class)->find($employeEntrepriseId);

            // Vérifier si l'employé existe et a le rôle de "Service Approvisionnement"
            if ($employeEntreprise) {
                $roles = explode(',', $employeEntreprise->getRoles());
                if (!in_array('ServiceApprovisionnement', $roles)) {
                    // Rediriger vers une autre route ou afficher un message d'erreur si l'employé n'est pas autorisé
                    $this->addFlash('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
                    return $this->redirectToRoute('connexion_employe');
                }
            } else {
                // Rediriger ou afficher un message d'erreur si l'employé n'est pas trouvé
                $this->addFlash('error', 'Employé non trouvé.');
                return $this->redirectToRoute('connexion_employe');
            }
        } else {
            // Rediriger ou afficher un message d'erreur si l'ID de l'employé n'est pas dans la session
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('connexion_employe'); 
        }

        if ($request->isMethod('POST')) {
            $nomFournisseur = $request->request->get('nomFournisseur');
            $quantite = $request->request->get('quantite');
            $nomProduit = $request->request->get('nomProduit');
            $etat = $request->request->get('etat');

            // Vérifier que les champs ne sont pas vides
            if (empty($nomFournisseur) || empty($quantite) || empty($nomProduit) || empty($etat)) {
                $this->addFlash('error', 'Tous les champs doivent être remplis.');
                return $this->redirectToRoute('ajouter_fournisseur');
            }

            // Créer une instance de l'entité Fournisseur
            $fournisseur = new Fournisseur();
            $fournisseur->setNomFournisseur($nomFournisseur);
            $fournisseur->setQuantite((int)$quantite); // Assurez-vous que la quantité est un entier
            $fournisseur->setNomProduit($nomProduit);
            $fournisseur->setEtat($etat);

            // Persister l'entité et l'enregistrer dans la base de données
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Commande Fournisseur ajouté avec succès !');

            // Rediriger vers la même page après l'ajout
            return $this->redirectToRoute('ajouter_fournisseur');
        }

        // Si la méthode est GET, renvoyer le template
        return $this->render('passercommandefournisseur.html.twig');
    }
}
