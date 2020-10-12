<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="my_account")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {

        //TODO:finir le controller pour affichage des données dans mon Compte

        return $this->render('my_account/index.html.twig', [
            'controller_name' => 'MyAccountController',
        ]);
    }
}
