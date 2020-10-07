<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\Tutorial;
use App\Entity\UserTutorial;
use App\Form\TutorialType;
use App\Repository\TutorialRepository;
use App\Repository\UserTutorialRepository;
use ContainerAKAPqlD\getUserTutorialRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tutorial")
 */
class TutorialController extends AbstractController
{
    //TODO: faire affichage de l'index en version mobile (lien sur l'image, correction de l'ombre portée)
    /**
     * @Route("/", name="tutorial_index", methods={"GET"})
     */
    public function index(TutorialRepository $tutorialRepository): Response
    {
        $em = $this->getDoctrine();

        $tutorials = $tutorialRepository->findAll();
        $userTutorials = $em->getRepository(UserTutorial::class)->findAll();
        /**$query = $tutorialRepository->createQueryBuilder('g')
            ->select('todo')
            ->leftJoin('g.userTutorial', 'todo', 'WITH', 'todo.id = ')**/
        $nbDone = 0;
        //TODO : faire une query propre pour le nombre de "done" plutôt que ce soit calculé dans le template twig


        return $this->render('tutorial/index.html.twig', [
            'tutorials' => $tutorials,
            'userTutorials' => $userTutorials,
            'nbDone' => $nbDone
        ]);
    }

    /**
     * @Route("/new", name="tutorial_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $tutorial = new Tutorial();

        $form = $this->createForm(TutorialType::class, $tutorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
            $entityManager = $this->getDoctrine()->getManager();
            $tutorial->setUser($this->getUser());
            $entityManager->persist($tutorial);
            $entityManager->flush();
            return $this->redirectToRoute('tutorial_index');

            } catch (\Exception $exception) {
                echo 'Exception reçue : ', $exception->getMessage(), "\n";
            }
        }

        return $this->render('tutorial/new.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tutorial_show", methods={"GET"})
     */
    public function show(Tutorial $tutorial): Response
    {
        $em = $this->getDoctrine();

        $steps = $em->getRepository(Step::class)->findBy(['tutorial' => $tutorial]);

        return $this->render('tutorial/show.html.twig', [
            'tutorial' => $tutorial,
            'steps' => $steps,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tutorial_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tutorial $tutorial): Response
    {
        $form = $this->createForm(TutorialType::class, $tutorial);
        $form->handleRequest($request);

        //TODO : faire en sorte que les formulaires puissent être édités sans avoir à re uploader une image !!

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $picture = $tutorial->getImageFile();
            $tutorial->setImageFile($picture);
            $em->persist($tutorial);
            $em->flush();
            return $this->redirectToRoute('tutorial_index');
        }

        return $this->render('tutorial/edit.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tutorial_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tutorial $tutorial): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tutorial->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tutorial);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tutorial_index');
    }
}
