<?php

namespace App\DataFixtures;

use App\Entity\Step;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StepFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $step1 = new Step();
        $step1->setDescription("Placer le scotch de façon à dessiner des figures géométriques");
        $step1->setTutorial($this->getReference("tuto1"));
        $step1->setPictureName("step1.jpg");
        $step1->setNumber(1);
        $manager->persist($step1);

        $step2 = new Step();
        $step2->setDescription("Peindre les triangles");
        $step2->setTutorial($this->getReference("tuto1"));
        $step2->setPictureName("step2.jpg");
        $step2->setNumber(2);
        $manager->persist($step2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TutorialFixtures::class,
        );
    }

}
