<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $userPasswordEncoder;

    /**
     *
     * @param UserPAsswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPAsswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("fr_FR");

        $user = new User();

        $user->setPseudo("Max")
            ->setEmail("maxence@gmail.com")
            ->setPassword($this->userPasswordEncoder->encodePassword($user, "motdepasse"))
            ->setRoles(["USERS_ROLE"]);

        $manager->persist($user);

        //création de 3 catégories
        for ($k = 1; $k <= 3; $k++) {
            $category = new Category();
            $category->setTitle($faker->sentence());

            $manager->persist($category);
            //création 10 articles par catégories
            for ($i = 0; $i < 10; $i++) {
                $article = new Article();

                $content = '<p>' . join($faker->paragraphs(rand(3, 5)), '</p> <p>') . '</p>';

                $article->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph())
                    ->setContent($content)
                    ->setCategory($category)
                    ->setUser($user)
                    ->setUrl($faker->imageUrl($width = 440, $height = 280));

                $manager->persist($article);
                //création de commentaires
                for ($j = 0; $j < (rand(2, 10)); $j++) {
                    $comment = new Comment();
                    $comment->setAuthor($user->getPseudo())
                        ->setUser($user)
                        ->setContent($faker->paragraph())
                        ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
