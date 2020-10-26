<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TutorialControllerTest extends WebTestCase
{
public function testShowTutorial()
{
$client = static::createClient();

$client->request('GET', '/tutorial/1');

$this->assertEquals(200, $client->getResponse()->getStatusCode());
}
}