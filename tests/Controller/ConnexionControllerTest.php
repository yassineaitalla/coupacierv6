<?php
 
 namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConnexionControllerTest extends WebTestCase
{
    public function testQuisommesnous(): void
    {
        // Créez un client HTTP pour simuler les requêtes
        $client = static::createClient();
        
        // Faites une requête GET à l'URL du contrôleur
        $client->request('GET', '/accueil');
        
        // Vérifiez que le code de statut HTTP est 200 OK
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        
        // Vérifiez que le contenu de la page contient le message attendu
        $this->assertSelectorTextContains('p', 'Bienvenue sur la page qui sommes nous !');
    }
}
