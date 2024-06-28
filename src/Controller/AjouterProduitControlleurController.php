<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AjouterProduitControlleurController extends AbstractController
{
    #[Route('/ajouter-produit', name: 'ajouter_produit')]
    public function ajouterProduit(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'ID de EmployeEntreprise depuis la session
        $employeEntrepriseId = $session->get('EmployeEntreprise_id');

        // Vérifier si l'ID de EmployeEntreprise est présent et égal à 2
        if (!$employeEntrepriseId || $employeEntrepriseId !== 1) {
            // Redirection ou gestion du cas où l'ID n'est pas correct
            return $this->redirectToRoute('ajout_produit'); // Redirige vers la page d'accueil ou une autre page appropriée
        } else {
            return $this->redirectToRoute('backoff');
        }

        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $nom = $request->request->get('nom');
            $prix = $request->request->get('prix');
            $masseLineaireKgMetre = $request->request->get('masselineaire');
            $nombredecoupe = $request->request->get('prixdecoupe');
            $coef = $request->request->get('coef');
            $remise = $request->request->get('remise');
            $description = $request->request->get('description');
            $longueur = $request->request->get('longueur');

            // Valider et convertir les données
            if (empty($nom) || empty($prix) || !is_numeric($prix)) {
                $this->addFlash('error', 'Veuillez fournir un nom valide et un prix numérique.');
                return $this->redirectToRoute('ajouter_produit');
            }

            $prix = (float) $prix;
            $masseLineaireKgMetre = !empty($masseLineaireKgMetre) ? (float) $masseLineaireKgMetre : null;
            $nombredecoupe = !empty($nombredecoupe) ? (float) $nombredecoupe : null;
            $coef = !empty($coef) ? (float) $coef : null;
            $remise = !empty($remise) ? (int) $remise : null;

            // Handle image upload
            $imageFile = $request->files->get('image');
            $imageFilename = null;

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $imageFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $imageFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('ajouter_produit');
                }
            }

            // Créer une instance de l'entité Produit
            $produit = new Produit();
            $produit->setNom($nom);
            $produit->setPrix($prix);
            $produit->setMasseLineaireKgMetre($masseLineaireKgMetre);
            $produit->setNombredecoupe($nombredecoupe);
            $produit->setCoef($coef);
            $produit->setRemise($remise);
            $produit->setLongueur($longueur);
            if ($imageFilename) {
                $produit->setImage($imageFilename);
            }
            $produit->setDescription($description);

            // Persister l'entité Produit
            $entityManager->persist($produit);
            $entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Le produit a été ajouté avec succès !');

            // Rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('accueil'); // Redirige vers la page d'accueil ou une autre page appropriée
        }

        // Si la méthode est GET, renvoyer simplement le template avec le formulaire
        return $this->render('ajoutproduitbd.html.twig');
    }
}
