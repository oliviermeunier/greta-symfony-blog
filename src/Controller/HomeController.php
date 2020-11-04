<?php

namespace App\Controller;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     */
    public function index(): Response
    {
        // Création de l'objet Faker qui va nous permettre de créer du faux contenu
        $faker = Factory::create('fr_FR');

        // Création d'un tableau vide pour stocker les objets Article
        $posts = [];

        // On boucle 10 fois pour créer 10 articles
        for ($i = 0; $i < 10; $i++) {

            // Création d'un objet standard avec les propriétés de l'article
            $post = new \StdClass();
            $post->title = $faker->sentence();
            $post->content = $faker->text(2500);
            $post->image = "https://picsum.photos/seed/post-$i/750/300";
            $post->author = $faker->name();
            $post->createdAt = $faker->dateTimeBetween('-3 years', 'now', 'Europe/Paris');

            // Ajout de l'objet au tableau
            array_push($posts, $post);
        }

        // Rendu du template Twig
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
