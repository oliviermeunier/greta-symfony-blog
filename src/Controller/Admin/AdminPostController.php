<?php


namespace App\Controller\Admin;

use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
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

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Article ajouté.');

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}