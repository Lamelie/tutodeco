<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{

    //Test qu'une page s'affiche après login.
    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // rechercher l'utilisateur admin
        $testUser = $userRepository->findOneByEmail('ameliederoche@hotmail.com');

        // simuler la connexion de l'admin
        $client->loginUser($testUser);
        $clientName = $testUser->getFirstName();

        // test d'accès à l'admin
        $this->assertEquals('Amélie', $clientName);

        $client->request('GET', 'http://127.0.0.1:8000/admin/?action=list&entity=Tutorial');
        $this->assertResponseIsSuccessful();
    }

    //Test que l'admin ne s'affiche pas pour un utilisateur non admin
    public function testVisitingWhileLoggedInNotAdmin()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // rechercher l'utilisateur
        $testUser = $userRepository->findOneByEmail('ethelhenrard@gmail.com');

        // simuler la connexion de l'utilisateur
        $client->loginUser($testUser);

        // test de refus d'accès à l'admin
        $client->request('GET', 'http://127.0.0.1:8000/admin/?action=list&entity=Tutorial');
        $this->assertResponseStatusCodeSame(403);
    }
}