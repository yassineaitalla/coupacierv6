<?php




namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class InformationsPersoCommandeController extends AbstractController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    #[Route('/commande/{id}', name: 'infocommande')]
    public function getClientInfo($id): Response
    {
        // Récupérer le client spécifique par son ID
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        // Vérifier si le client existe
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé.');
        }

        // Récupérer les informations spécifiques du client
        $nom = $client->getNom();
        $prenom = $client->getPrenom();
        $email = $client->getEmail();
        $telephone = $client->getTelephone();
        $motdepasse = $client->getMotdepasse();

        // Obtenir la session à partir de la request stack
        $session = $this->requestStack->getSession();
        $session->set('client_info', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'motdepasse' => $motdepasse,
        ]);

        // Transmettre les informations du client au template Twig
        return $this->render('commande.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone,
            'motdepasse' => $motdepasse,
        ]);
    }

    #[Route('/affiche-client', name: 'affiche_client')]
    public function afficheClient(): Response
    {
        // Obtenir la session à partir de la request stack
        $session = $this->requestStack->getSession();
        $clientInfo = $session->get('client_id', []);

        // Vérifier si les informations du client existent dans la session
        if (empty($clientInfo)) {
            throw $this->createNotFoundException('Aucune information client trouvée dans la session.');
        }

        // Transmettre les informations du client au template Twig
        return $this->render('affiche_client.html.twig', [
            'nom' => $clientInfo['nom'],
            'prenom' => $clientInfo['prenom'],
            'email' => $clientInfo['email'],
            'telephone' => $clientInfo['telephone'],
            'motdepasse' => $clientInfo['motdepasse'],
        ]);
    }

    #[Route('/commande', name: 'verification5')]
    public function verificationConnexion(SessionInterface $session): Response
{
    // Vérifions si l'utilisateur est connecté en vérifiant la présence de son ID dans la session
    $clientId = $session->get('client_id');

    if ($clientId) {
        // Si l'utilisateur est connecté, redirigeons vers la page de récupération des informations
        return $this->redirectToRoute('recup_informations', ['id' => $clientId]);
    } else {
        // Si l'utilisateur n'est pas connecté, redirigeons vers la page de connexion
        return $this->redirectToRoute('pageconnexion');
    }
}



}
