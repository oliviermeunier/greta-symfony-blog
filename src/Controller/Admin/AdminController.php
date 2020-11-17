<?php


namespace App\Controller\Admin;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'posts' => $postRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }
}