<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        
        // list of fake articles 
        $articles = [];
        for ($i = 0; $i < 15; $i++){
            $article = new Article();
            $article->setTitle($faker->text(120));
            $article->setContent($faker->text(1500));

            $articles[] = $article;

            $manager->persist($article);
        }

        // list of fake comments for articles
        foreach ($articles as $article) {
            $random = mt_rand(0,3);

            for($i = 0; $i <=$random; $i++ ){
                $comment = new Comment();
                $comment->setContent($faker->text(200));
                $comment->setArticle($article);

                $manager->persist($comment);
            }
        }

        // add a user
        $user = new User();
        $user->setUsername("user");
        $user->setEmail('user@user.fr');
        $user->setPassword($this->passwordHasher->hashPassword($user, "user"));
        $user->setRoles([]);
        $manager->persist($user);

        // add a admin
        $admin = new User();
        $admin->setUsername("admin");
        $admin->setEmail('admin@admin.fr');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "admin"));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $manager->flush();
    }
}
