<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\Tutorial;
use App\Entity\UserTutorial;
use App\Form\TutorialType;
use App\Repository\TutorialRepository;
use App\Repository\UserTutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @Route("/", name="tutorial_index", methods={"GET"})
     */
    public function index(TutorialRepository $tutorialRepository): Response
    {
        $em = $this->getDoctrine();

        $tutorials = $tutorialRepository->findAll();
        $userTutorials = $em->getRepository(UserTutorial::class)->findAll();

        return $this->render('tutorial/index.html.twig', [
            'tutorials' => $tutorials,
            'userTutorials' => $userTutorials,
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
     * Permet de marquer comme fait ou pas fait un tutoriel
     *
     * @Route("/{id}/done", name="tutorial_done")
     *
     * @param Tutorial $tutorial
     * @param EntityManagerInterface $manager
     * @param UserTutorialRepository $userTutorialRepository
     * @return Response
     */
    public function done(Tutorial $tutorial, EntityManagerInterface $manager, UserTutorialRepository $userTutorialRepository): Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => "unauthorized"
        ], 403);

        if($tutorial->isDoneByUser($user)){
            $done = $userTutorialRepository->findOneBy([
                'tutorial'=> $tutorial,
                'user'=>$user,
                'done' => 1,
            ]);
            $manager->remove($done);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => "done supprimé",
                'dones' => $userTutorialRepository->count([
                        'tutorial'=> $tutorial,
                        'done' => 1
                    ]),
                'todos' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'todo' => 1
                ])
            ], 200);
        }
        $done = new UserTutorial();
        $done->setTutorial($tutorial)
            ->setUser($user)
            ->setDone(1);
        if($tutorial->isTodoByUser($user)){
            $todo = $userTutorialRepository->findOneBy([
                'tutorial'=> $tutorial,
                'user'=>$user,
                'todo' => 1,
            ]);
            $manager->remove($todo);
        }

        $manager->persist($done);
        $manager->flush();

            return $this->json([
                "code"=>200,
                "message"=>"done ajouté",
                'dones' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'done' => 1
                    ]),
                'todos' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'todo' => 1
                ])
            ], 200);
    }

    /**
     * Permet de mettre ou retirer un tutoriel de sa todo list
     *
     * @Route("/{id}/todo", name="tutorial_todo")
     *
     * @param Tutorial $tutorial
     * @param EntityManagerInterface $manager
     * @param UserTutorialRepository $userTutorialRepository
     * @return Response
     */
    public function todo(Tutorial $tutorial, EntityManagerInterface $manager, UserTutorialRepository $userTutorialRepository): Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => "unauthorized"
        ], 403);

        if($tutorial->isTodoByUser($user)){
            $todo = $userTutorialRepository->findOneBy([
                'tutorial'=> $tutorial,
                'user'=>$user,
                'todo' => 1,
            ]);
            $manager->remove($todo);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => "todo supprimé",
                'todos' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'todo' => 1
                ]),
                'dones' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'done' => 1
                ])
            ], 200);
        }
        $todo = new UserTutorial();
        $todo->setTutorial($tutorial)
            ->setUser($user)
            ->setTodo(1);
        if($tutorial->isDoneByUser($user)){
            $done = $userTutorialRepository->findOneBy([
                'tutorial'=> $tutorial,
                'user'=>$user,
                'done' => 1,
            ]);
            $manager->remove($done);
        }
        $manager->persist($todo);
        $manager->flush();

        return $this->json([
            "code"=>200,
            "message"=>"todo ajouté",
            'dones' => $userTutorialRepository->count([
                'tutorial'=> $tutorial,
                'done' => 1
            ]),
            'todos' => $userTutorialRepository->count([
                'tutorial'=> $tutorial,
                'todo' => 1
            ])
        ], 200);
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
            $picturefile = $form['imageFile']->getData();
            if (!$picturefile) {
                $picture = $tutorial->getImageFile();
                $tutorial->setImageFile($picture);
            }
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
