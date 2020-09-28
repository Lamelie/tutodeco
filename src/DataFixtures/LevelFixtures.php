<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $level = new \App\Entity\Level();
            $level->setLabel('Difficulté '.$i);
            $manager->persist($level);
            $this->addReference("diff".$i, $level);
        }

        $manager->flush();
    }
}
