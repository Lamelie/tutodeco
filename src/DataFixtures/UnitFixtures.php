<?php

namespace App\DataFixtures;

use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UnitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $unit1= new Unit();
        $unit1 ->setLabel("mètre");
        $manager->persist($unit1);
        $this->addReference("metre", $unit1);

        $unit2= new Unit();
        $unit2 ->setLabel("litre");
        $manager->persist($unit2);
        $this->addReference("litre", $unit2);

        $unit3= new Unit();
        $unit3 ->setLabel("centimètre");
        $manager->persist($unit3);
        $this->addReference("centimetre", $unit3);

        $manager->flush();
    }
}
