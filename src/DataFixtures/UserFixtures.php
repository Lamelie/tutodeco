<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail("ameliederoche@hotmail.com");
        $user1->setPassword($this->passwordEncoder->encodePassword(
            $user1,
            'the_new_password'
        ));
        $user1->setDescription("J'ai 33ans et j'habite Rennes. J'aime particulièrement travailler le bois.");
        $user1->setFirstName("Amélie");
        $user1->setLastName("Lorans");
        $user1->setNickname("La fille de Rennes");
        $user1->setPictureName("amelie.jpg");
        $user1->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user1);
        $this->addReference("amelie", $user1);


        $user2 = new User();
        $user2->setEmail("ethelhenrard@gmail.com");
        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'the_new_password'
        ));
        $user2->setDescription("J'ai 37ans et j'habite Rennes. Je fais beaucoup de couture.");
        $user2->setFirstName("Ethel");
        $user2->setLastName("Henrard");
        $user2->setPictureName("ethel.png");
        $user2->setRoles(["ROLE_USER"]);
        $manager->persist($user2);
        $this->addReference("ethel", $user2);

        $user3 = new User();
        $user3->setEmail("fracadero@gmail.com");
        $user3->setPassword($this->passwordEncoder->encodePassword(
            $user3,
            'the_new_password'
        ));
        $user3->setDescription("J'ai 65ans et j'habite Morlaix. J'aime beaucoup présenter mes projets de déco.");
        $user3->setFirstName("Catherine");
        $user3->setLastName("Roche");
        $user3->setRoles(["ROLE_USER"]);
        $manager->persist($user3);
        $this->addReference("cath", $user3);


        $manager->flush();
    }
}
