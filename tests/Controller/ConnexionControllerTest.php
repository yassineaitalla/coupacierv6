<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;

class ConnexionControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::$kernel->getContainer()->get(EntityManagerInterface::class);
    }

    public function testSuccessfulLogin()
    {
        // Création d'un utilisateur de test
        $email = 'test@example.com';
        $motdepasse = 'password123';
        $client = new Client();
        $client->setEmail($email);
        $client->setMotdepasse(password_hash($motdepasse, PASSWORD_DEFAULT));
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        // Simuler une requête POST avec les bonnes informations
        $this->client->request('POST', '/connexion', [
            'email' => $email,
            'motdepasse' => $motdepasse,
        ]);

        // Vérifier que la réponse est une redirection vers la route 'produits'
        $this->assertResponseRedirects('/produits');

        // Vérifier que le flash message est présent
        $crawler = $this->client->followRedirect();
        $session = $this->client->getContainer()->get('session');
        $flashMessages = $session->getFlashBag()->get('success');
        $this->assertContains('Vous êtes connecté.', $flashMessages);
    }

    public function testFailedLoginInvalidEmail()
    {
        // Simuler une requête POST avec un email invalide
        $this->client->request('POST', '/connexion', [
            'email' => 'invalid@example.com',
            'motdepasse' => 'password123',
        ]);

        // Vérifier que la réponse est une redirection vers la route 'pageconnexion'
        $this->assertResponseRedirects('/pageconnexion');

        // Vérifier que le flash message est présent
        $crawler = $this->client->followRedirect();
        $session = $this->client->getContainer()->get('session');
        $flashMessages = $session->getFlashBag()->get('error');
        $this->assertContains('Adresse e-mail incorrecte.', $flashMessages);
    }

    public function testFailedLoginInvalidPassword()
    {
        // Création d'un utilisateur de test avec un mot de passe incorrect
        $email = 'test@example.com';
        $motdepasse = 'password123';
        $client = new Client();
        $client->setEmail($email);
        $client->setMotdepasse(password_hash($motdepasse, PASSWORD_DEFAULT));
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        // Simuler une requête POST avec un mot de passe invalide
        $this->client->request('POST', '/connexion', [
            'email' => $email,
            'motdepasse' => 'wrongpassword',
        ]);

        // Vérifier que la réponse est une redirection vers la route 'pageconnexion'
        $this->assertResponseRedirects('/pageconnexion');

        // Vérifier que le flash message est présent
        $crawler = $this->client->followRedirect();
        $session = $this->client->getContainer()->get('session');
        $flashMessages = $session->getFlashBag()->get('error');
        $this->assertContains('Mot de passe incorrect.', $flashMessages);
    }
}
