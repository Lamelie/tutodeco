<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use http\Exception;
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
        if ($user->getRoles() == ['ROLE_USER','ROLE_DECO'] OR $user == $this->getUser()) {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
        }
        else {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'accéder à cette page");
        }
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
