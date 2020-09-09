<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Compound;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker/Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);

    }

    public function loadBlogPosts(ObjectManager $manager){
        for($i=0; $i<100; $i++){
            $user = $this->getReference('user_admin');
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setAuthor($user);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setSlug($this->faker->slug);

            $this->addReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }
        $manager->flush();

    }

    public function loadComments(ObjectManager $manager){
        for($i=0; $i<100; $i++){
            for($j=0; $j<rand(1, 10); $j++){
                $comment = new Comment();

                $comment->setContent($this->faker->realText(150));
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($this->getReference('user_admin'));
                $comment->setBlogPost($this->getReference("blog_post_$i"));
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager){
        $user = new User();
        $user->setName('Walid Maarad');
        $user->setEmail('walsam@hotmail.com');
        $user->setUsername('walsam');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123456'
        ));

        $this->addReference('user_admin', $user);
        $manager->persist($user);
        $manager->flush();
    }
}
