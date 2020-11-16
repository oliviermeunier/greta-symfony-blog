<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/new", name="user.new")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre compte a bien été créé, vous pouvez vous connecter.');

            return $this->redirectToRoute('security.login');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
