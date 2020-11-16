<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{slug}", name="post.index")
     * @param Post $post
     * @return Response
     */
    public function index(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        // Création d'un formulaire
        $form = $this->createForm(CommentType::class);

        // On va traiter les données du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On réucpère les données du formulaire
            $comment = $form->getData();

            // On attribut au champ post l'article sur lequel on se trouve
            $comment->setPost($post);

            $comment->setUser($this->getUser());

            // Persistence des données
            $manager->persist($comment);
            $manager->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès.');

            // Redirection pour perdre les données de la requête
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{slug}/add-comment", name="post.ajax.comment", methods={"POST"})
     */
    public function ajaxAddComment(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment = $form->getData();
            $comment->setPost($post);

            $comment->setUser($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            return $this->render('post/_comment.html.twig', [
                'comment' => $comment
            ]);
        }
    }
}
