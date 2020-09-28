<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Tutorial;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $comment1 = new Comment();
        $comment1->setContent("Très bon tutoriel, merci !");
        $comment1->setTutorial($this->getReference("tuto1"));
        $comment1->setUser($this->getReference("amelie"));
        $manager->persist($comment1);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            TutorialFixtures::class,
        );
    }
}
