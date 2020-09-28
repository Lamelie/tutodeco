<?php

namespace App\DataFixtures;

use App\Entity\Tool;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ToolFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tool1= new Tool();
        $tool1 ->setLabel("Scie sauteuse");
        $manager->persist($tool1);
        $this->addReference("ss", $tool1);

        $tool2= new Tool();
        $tool2 ->setLabel("tournevis");
        $manager->persist($tool2);
        $this->addReference("tournevis", $tool2);

        $tool3= new Tool();
        $tool3 ->setLabel("marteau");
        $manager->persist($tool3);
        $this->addReference("marteau", $tool3);

        $manager->flush();
    }
}
