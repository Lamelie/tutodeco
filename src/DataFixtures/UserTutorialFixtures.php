<?php

namespace App\DataFixtures;

use App\Entity\UserTutorial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;

class UserTutorialFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userTuto = new UserTutorial();
        $userTuto->setTutorial($this->getReference("tuto1"));
        $userTuto->setUser($this->getReference("amelie"));
        $userTuto->setRate(5);
        $userTuto->setDone(1);
        $userTuto->setTodo(0);
        $manager->persist($userTuto);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TutorialFixtures::class,
        );
    }
}
