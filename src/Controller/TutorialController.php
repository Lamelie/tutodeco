<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Step;
use App\Entity\Tutorial;
use App\Entity\UserTutorial;
use App\Form\CommentType;
use App\Form\TutorialType;
use App\Repository\TutorialRepository;
use App\Repository\UserTutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(TutorialRepository $tutorialRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine();

        $data = $tutorialRepository->findby(
            ['validation' => 1],
            ['createdAt' => 'DESC']
        );
        $userTutorials = $em->getRepository(UserTutorial::class)->findAll();

        // Paginer les résultats de la requete

        $tutorials = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );


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
            $tutorial->setValidation(0);
            $entityManager->persist($tutorial);

            $user = $tutorial->getUser();
            $entityManager->persist($user);

            $entityManager->flush();
            return $this->redirectToRoute('thankyou');

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
     * @Route("/new/thankyou", name="thankyou")
     * @return Response
     */
    public function confirmation() : Response
    {
        return $this->render('tutorial/thankyou.html.twig');

    }

    /**
     * @Route("/{id}", name="tutorial_show", methods={"GET", "POST"})
     */
    public function show(Tutorial $tutorial, Request $request): Response
    {
        //redirection si le tutoriel n'est pas validé.
        if ($tutorial->getValidation() == 0 ) {
            throw $this->createNotFoundException('Ce tutoriel n\'est pas validé');
        }
        $em = $this->getDoctrine();

        //ajout des étapes
        $steps = $em->getRepository(Step::class)->findBy(
            ['tutorial' => $tutorial],
                ['number' => 'ASC']
            );

        //ajout du formulaire de commentaires
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setUser($this->getUser());
            $comment->setTutorial($tutorial);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('tutorial_show',['id' => $tutorial->getId()]);
        }

        return $this->render('tutorial/show.html.twig', [
            'tutorial' => $tutorial,
            'steps' => $steps,
            'form' => $form->createView(),
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

        //vérifie que l'utilisateur en ligne est bien autorisé à effectuer l'opération
        if(!$user) return $this->json([
            'code' => 403,
            'message' => "unauthorized"
        ], 403);

        //Recherche de la ligne correspondant à ce "fait" dans userTutorial
        if($tutorial->isDoneByUser($user)){
            $done = $userTutorialRepository->findOneBy([
                'tutorial'=> $tutorial,
                'user'=>$user,
                'done' => 1,
            ]);
            //suppression de la donnée
            $manager->remove($done);
            $manager->flush();

            //envoi de l'information via requête HTTP
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
     * Permet de mettre ou retirer un tutoriel de sa todo-list
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

        //utilisateur non connecté --> non autorisé (statut 403)
        if(!$user) return $this->json([
            'code' => 403,
            'message' => "unauthorized"
        ], 403);

        //si le tutoriel est déjà en Todolist : suppression de la todolist
        if($tutorial->isTodoByUser($user)){
            //requete permettant de supprimer de la liste des Todos.
            $todo = $userTutorialRepository->findOneBy([
                'tutorial'=> $tutorial,
                'user'=>$user,
                'todo' => 1,
            ]);
            //suppression
            $manager->remove($todo);
            $manager->flush();

            //envoi d'une requête http en format json
            return $this->json([
                'code' => 200,
                'message' => "todo supprimé",
                //compte le nombre de todos pour ce tutoriel
                'todos' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'todo' => 1
                ]),
                //compte le nombre de faits pour ce tutoriel
                'dones' => $userTutorialRepository->count([
                    'tutorial'=> $tutorial,
                    'done' => 1
                ])
            ], 200);
        }
        //si le tutoriel n'était pas en todolist : ajout en todolist
        $todo = new UserTutorial();
        $todo->setTutorial($tutorial)
            ->setUser($user)
            ->setTodo(1);
        //si le tutoriel était marqué comme fait : suppression de la liste des faits.
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

        //envoi d'une requête http en format json
        return $this->json([
            "code"=>200,
            "message"=>"todo ajouté",
            //compte nombre de faits pour ce tuto
            'dones' => $userTutorialRepository->count([
                'tutorial'=> $tutorial,
                'done' => 1
            ]),
            //compte nombre de todos pour ce tuto
            'todos' => $userTutorialRepository->count([
                'tutorial'=> $tutorial,
                'todo' => 1
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="tutorial_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Tutorial $tutorial): Response
    {
        //empeche la modification si l'utilisateur n'est pas l'auteur et si le tutoriel a déjà été validé
        if ($this->getUser() != $tutorial->getUser() or $tutorial->getValidation() == 1) {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'accéder à cette page");
        }
        $form = $this->createForm(TutorialType::class, $tutorial);
        $form->handleRequest($request);

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
