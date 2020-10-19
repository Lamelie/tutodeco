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

        $data = $repository->findAll();

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $keyword = $searchForm->getData()->getTitle();

            $data = $repository->search($keyword);

            if ($data == null) {
                $this->addFlash('erreur', 'Aucun tutoriel contenant ce mot clé n\'a été trouvé, essayez en un autre.');
            }
        }

        // Paginate the results of the query
        $tutorials = $paginator->paginate(
        // Doctrine Query, not results
            $data,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            6
        );

        return $this->render('default/index.html.twig',[
            'tutorials' => $tutorials,
            'searchForm' => $searchForm->createView()
        ]);
    }

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