<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Cost;


class CostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cost1= new Cost();
        $cost1 ->setLabel("Bon marché");
        $manager->persist($cost1);
        $this->addReference("bm", $cost1);


        $cost2= new Cost();
        $cost2 ->setLabel("Moyen");
        $manager->persist($cost2);
        $this->addReference("moy", $cost2);


        $cost3= new Cost();
        $cost3 ->setLabel("Assez cher");
        $manager->persist($cost3);
        $this->addReference("ac", $cost3);


        $manager->flush();
    }
}
