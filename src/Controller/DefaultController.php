<?php

namespace App\Controller;

use App\Repository\TutorialRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
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

    public function recherche(Request $request, TutorialRepository $repository, PaginatorInterface $paginator) {

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        $data = $repository->findAll();

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $title = $searchForm->getData()->getTitle();

            $data = $repository->search($title);


            if ($data == null) {
                $this->addFlash('erreur', 'Aucun article contenant ce mot clé n\'a été trouvé, essayez en un autre.');
            }
        }

        // Paginate the results of the query
        $articles = $paginator->paginate(
        // Doctrine Query, not results
            $data,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );

        return $this->render('biblio/search.html.twig',[
            'articles' => $articles,
            'searchForm' => $searchForm->createView()
        ]);
    }


}
