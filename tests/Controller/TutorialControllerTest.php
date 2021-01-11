<?php

namespace App\Tests\Controller;

use App\Entity\UserTutorial;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class TutorialControllerTest extends WebTestCase
    {
        //test d'affichage d'une page sans besoin de connexion.
        public function testShowTutorial()
        {

            $client = static::createClient();
    
            $client->request('GET', '/tutorial/1');
    
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }

}