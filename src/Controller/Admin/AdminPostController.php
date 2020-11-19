<?php


namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/admin/post/new", name="admin.post.new")
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            $post->setUser($this->getUser());

            if ($imageUploadFile = $form->get('imageFile')->getData()) {

                $originalFilename = pathinfo($imageUploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = Urlizer::urlize($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageUploadFile->guessExtension();

                $imageUploadFile->move(
                    $this->getParameter('post_image_directory'),
                    $newFilename
                );

                $post->setImage($newFilename);
            }

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Article ajouté.');

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/post/edit/{id}", name="admin.post.edit")
     * @return Response
     */
    public function edit(Post $post, Request $request, EntityManagerInterface $manager, Filesystem $filesystem): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            $post->setUser($this->getUser());

            if ($imageUploadFile = $form->get('imageFile')->getData()) {

                if ($currentFilename = $post->getImage()) {
                    $currentPath = $this->getParameter('post_image_directory') . '/' . $currentFilename;
                    if ($filesystem->exists($currentPath)) {
                        $filesystem->remove($currentPath);
                    }
                }

                $originalFilename = pathinfo($imageUploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = Urlizer::urlize($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageUploadFile->guessExtension();

                $imageUploadFile->move(
                    $this->getParameter('post_image_directory'),
                    $newFilename
                );

                $post->setImage($newFilename);
            }

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Article modifié.');

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/post/remove/{id}", name="admin.post.remove")
     */
    public function remove(EntityManagerInterface $manager, Post $post, Filesystem $filesystem, Request $request)
    {
        $id = $post->getId();

        // Suppression du fichier image
        if ($currentFilename = $post->getImage()) {
            $currentPath = $this->getParameter('post_image_directory') . '/' . $currentFilename;
            if ($filesystem->exists($currentPath)) {
                $filesystem->remove($currentPath);
            }
        }

        // Suppression de l'entité
        $manager->remove($post);
        $manager->flush();

        if ($request->isXmlHttpRequest()) {
            return $this->json($id);
        }

        $this->addFlash('success', 'Article supprimé avec succès.');

        return $this->redirectToRoute('admin.index');
    }
}