<?php

namespace App\DataFixtures;

use App\Entity\Tutorial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TutorialFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tutorial = new Tutorial();
        $tutorial->setTitle("Pot à crayon coloré en bois");
        $tutorial->setDescription("Réalisez ce joli pot à crayon coloré et graphique. Utile pour ranger tous ses crayons !");
        $tutorial->setPicture("tutoriel1.jpg");
        $tutorial->setDuration(120);
        $tutorial->setValidation("En cours de validation");
        $tutorial->setCost($this->getReference("bm"));
        $tutorial->setUser($this->getReference("ethel"));
        $tutorial->setLevel($this->getReference("diff1"));
        $tutorial->addMaterial($this->getReference("peinture"));
        $tutorial->addTool($this->getReference("ss"));
        $manager->persist($tutorial);
        $this->addReference("tuto1", $tutorial);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            MaterialFixtures::class,
        );
    }
}
