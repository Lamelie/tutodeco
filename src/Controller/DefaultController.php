<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'Amélie',
        ]);
    }

    public function tutoNav ()
    {
        return $this->render("default/_menu.html.twig");
    }
}
