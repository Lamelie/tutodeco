<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Entity\User;
use App\Repository\TutorialRepository;
use App\Repository\UserTutorialRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="my_account", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(UserTutorialRepository $userTutorialRepository)
    {
        $user = $this->getUser();

        $tutodones = $userTutorialRepository->findBy([
            'user' => $user,
            'done' => 1,
        ]);

        $todos = $userTutorialRepository->findBy([
            'user' => $user,
            'todo' => 1,
        ]);

        return $this->render('my_account/index.html.twig', [
            'todos' => $todos,
            'dones' => $tutodones,
        ]);
    }
}
