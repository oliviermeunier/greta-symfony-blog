<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);

        // Rendu du template Twig
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
