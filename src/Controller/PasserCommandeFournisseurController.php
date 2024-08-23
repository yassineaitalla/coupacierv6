<?php






namespace App\Controller;

use App\Entity\Fournisseur;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasserCommandeFournisseurController extends AbstractController
{
    #[Route('/ajouter/fournisseur', name: 'ajouter_fournisseur')]
    public function ajouterFournisseur(Request $request, EntityManagerInterface $entityManager): Response
    {
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
