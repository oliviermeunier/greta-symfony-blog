<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\CommentFactory;
use App\Factory\PostFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de 10 users
        UserFactory::new()->createMany(10);

        // Création de 5 catégories grâce à la CategoryFactory, l'usine à fabriquer des catégories
        CategoryFactory::new()->createMany(5);

        // Création de 10 articles grâce à la PostFactory, l'usine à fabriquer des articles
        PostFactory::new()->createMany(10);

        // Création de 100 commentaires
        CommentFactory::new()->createMany(100);

        // Création d'un administrateur
        UserFactory::new()->create([
            'roles' => ['ROLE_ADMIN'],
            'password' => 'admin',
            'email' => 'admin@admin.com'
        ]);

        // Enregistrement des objets créés en base de données
        $manager->flush();
    }
}
