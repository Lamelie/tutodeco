<?php

namespace App\Tests\Entity;

use App\Entity\Tutorial;
use App\Entity\User;
use App\Entity\UserTutorial;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TutorialTest extends TestCase
{
    public function testIsDoneByUser()
    {
        $tutorial = new Tutorial();
        $user = new User();
        $userTutorial = new UserTutorial();
        $userTutorial->setUser($user);
        $userTutorial->setTutorial($tutorial);
        $userTutorial->setDone(true);

        $result = $tutorial->isDoneByUser($user);

        $this->assertEquals(true, $result);
    }

    public function testIsTodoByUser()
    {
        $tutorial = new Tutorial();
        $user = new User();
        $userTutorial = new UserTutorial();
        $userTutorial->setUser($user);
        $userTutorial->setTutorial($tutorial);
        $userTutorial->setTodo(true);

        $result = $tutorial->isTodoByUser($user);

        $this->assertEquals(true, $result);
    }
}

