<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail("ameliederoche@hotmail.com");
        $user1->setDescription("J'ai 33ans et j'habite Rennes. J'aime particulièrement travailler le bois.");
        $user1->setFirstName("Amélie");
        $user1->setLastName("Lorans");
        $user1->setUsername("La fille de Rennes");
        $user1->setPictureName("amelie.jpg");
        $manager->persist($user1);
        $this->addReference("amelie", $user1);


        $user2 = new User();
        $user2->setEmail("ethelhenrard@gmail.com");
        $user2->setDescription("J'ai 37ans et j'habite Rennes. Je fais beaucoup de couture.");
        $user2->setFirstName("Ethel");
        $user2->setLastName("Henrard");
        $manager->persist($user2);
        $this->addReference("ethel", $user2);

        $user3 = new User();
        $user3->setEmail("fracadero@gmail.com");
        $user3->setDescription("J'ai 65ans et j'habite Morlaix. J'aime beaucoup présenter mes projets de déco.");
        $user3->setFirstName("Catherine");
        $user3->setLastName("Roche");
        $manager->persist($user3);
        $this->addReference("cath", $user3);


        $manager->flush();
    }
}
