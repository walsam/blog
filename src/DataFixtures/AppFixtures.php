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

    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'walsam@mail.com',
            'name' => 'Walid Maarad',
            'password' => 'Password1',
            'roles' => [User::ROLE_SUPERADMIN]
        ],
        [
            'username' => 'michou',
            'email' => 'michou@blog.com',
            'name' => 'Oussama Maachou',
            'password' => 'Password1',
            'roles' => [User::ROLE_ADMIN]
        ],
        [
            'username' => 'doudou',
            'email' => 'doudou@blog.com',
            'name' => 'Abdessamed Saidi',
            'password' => 'Password1',
            'roles' => [User::ROLE_WRITER]
        ],
        [
            'username' => 'Soussou',
            'email' => 'soussou@blog.com',
            'name' => 'Soufiane Nsabi',
            'password' => 'Password1',
            'roles' => [User::ROLE_WRITER]
        ],
        [
            'username' => 'redone',
            'email' => 'red1@blog.com',
            'name' => 'Redwane Bessisse',
            'password' => 'Password1',
            'roles' => [User::ROLE_EDITOR]
        ],
        [
            'username' => 'redtef',
            'email' => 'redtef@blog.com',
            'name' => 'Hamza Takouit',
            'password' => 'Password1',
            'roles' => [User::ROLE_COMMENTATOR]
        ]
    ];

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
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $authorReference = $this->getRandomUserReference($blogPost);
            $blogPost->setAuthor($authorReference);
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
                $authorReference = $this->getRandomUserReference($comment);
                $comment->setAuthor($authorReference);
                $comment->setBlogPost($this->getReference("blog_post_$i"));
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager){
        foreach (self::USERS as $userFixture){
            $user = new User();
            $user->setName($userFixture['name']);
            $user->setEmail($userFixture['email']);
            $user->setUsername($userFixture['username']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userFixture['password']
            ));
            $user->setRoles($userFixture['roles']);

            $this->addReference('user_'. $userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    protected function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0, 5)];

        if ($entity instanceof BlogPost && !count(
                array_intersect(
                    $randomUser['roles'],
                    [
                        User::ROLE_SUPERADMINE,
                        User::ROLE_ADMIN,
                        User::ROLE_WRITER
                    ]
                )
            )) {
            return $this->getRandomUserReference($entity);
        }

        if ($entity instanceof Comment && !count(
                array_intersect(
                    $randomUser['roles'],
                    [
                        User::ROLE_SUPERADMINE,
                        User::ROLE_ADMIN,
                        User::ROLE_WRITER,
                        User::ROLE_COMMENTATOR
                    ]
                )
            )) {
            return $this->getRandomUserReference($entity);
        }

        return $this->getReference(
            'user_' .$randomUser['username']
        );
    }
}
