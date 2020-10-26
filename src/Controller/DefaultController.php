<?php

namespace App\Controller;

use App\Form\TutorialSearchType;
use App\Repository\TutorialRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"POST", "GET"})
     */

    public function index(Request $request, TutorialRepository $repository, PaginatorInterface $paginator) {

        $searchForm = $this->createForm(TutorialSearchType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false
        ]);
        $searchForm->handleRequest($request);

        $data = $repository->findby(
            ['validation' => 1]);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $keyword = $searchForm->getData()->getTitle();
            $durationMax = $searchForm->getData()->getDuration();
            $level = $searchForm->getData()->getLevel();
            $cost = $searchForm->getData()->getCost();
            if (!$durationMax) {
                $durationMax = 10000;
            }

            $data = $repository->searchPlus($keyword, $durationMax, $level, $cost);

        }

        // Paginer les résultats de la requete
        $tutorials = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('default/index.html.twig',[
            'tutorials' => $tutorials,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param TutorialRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tutoNav (Request $request, TutorialRepository $repository)
    {
        $searchForm = $this->createForm(TutorialSearchType::class, null, [
            'method' => 'GET',
            'action' => $this->generateUrl('homepage'),
            'csrf_protection' => false
        ]);

        return $this->render("default/_menu.html.twig", [
            'searchForm' => $searchForm->createView()
        ]);
    }



}