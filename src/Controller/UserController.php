<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{slug}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        if ($user->getNbTuto() > 0 or $user == $this->getUser()) {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
        }
        else {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'accéder à cette page");
        }
    }

    /**
     * Permet de s'abonner ou se désabonner d'un décorateur
     *
     * @Route("/{slug}/subscribe", name="deco_subscribe")
     *
     * @param User $userTo
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function subscribe(User $userTo, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        $userFrom = $this->getUser();

        if(!$userFrom) return $this->json([
            'code' => 403,
            'message' => "unauthorized"
        ], 403);

        //Recherche de la ligne correspondant à ce "subscribed"(=userFrom) dans la table user
//        if($userTo->isSubscribedByUser($userFrom)){
//
//            //requête à corriger
//            $subscribed = $userRepository->findOneBy([
//                'userTos'=> $userTo,
//                'userFroms'=>$userFrom,
//            ]);
//            //suppression de la donnée
//            $manager->remove($subscribed);
//            $manager->flush();
//
//            //envoi de l'information via requête HTTP
//            return $this->json([
//                'code' => 200,
//                'message' => "abonnement supprimé",
//                'subscribers' => $userRepository->count([
//                    'userTos'=> $userTo,
//                ]),
//            ], 200);
//        }

        $userTo->addUserFrom($userFrom);
        $manager->persist($userTo);
        $manager->flush();

        return $this->json([
            'code'=>200,
            'message'=>"abonnement ajouté"
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, User $user): Response
    {
        if ($this->getUser() != $user) {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'accéder à cette page");
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $slugger = new AsciiSlugger();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($user->getPassword());
            if (!$form->get('nickname')->getData()) {
                $slug = $user->getSlug();
            }else{
                $slug = $slugger->slug($form->get('nickname')->getData())->lower();
            }

            $user->setSlug($slug);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('my_account');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('homepage');
    }
}
