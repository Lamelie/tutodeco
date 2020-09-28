<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $tag = new \App\Entity\Tag();
            $tag->setLabel('Tag '.$i);
            $manager->persist($tag);
            $this->addReference("tag".$i, $tag);
        }

        $manager->flush();
    }
}
