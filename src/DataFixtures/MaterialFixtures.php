<?php

namespace App\DataFixtures;

use App\Entity\Material;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MaterialFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $material1 = new Material();
        $material1->setLabel("Tissu");
        $material1->setUnit($this->getReference("metre"));
        $manager->persist($material1);
        $this->addReference("tissu", $material1);


        $material2 = new Material();
        $material2->setLabel("Ruban");
        $material2->setUnit($this->getReference("centimetre"));
        $manager->persist($material2);
        $this->addReference("ruban", $material2);


        $material3 = new Material();
        $material3->setLabel("Peinture");
        $material3->setUnit($this->getReference("litre"));
        $manager->persist($material3);
        $this->addReference("peinture", $material3);


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UnitFixtures::class,
        );
    }
}
