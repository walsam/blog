<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('this is a post');
        $blogPost->setPublished(new \DateTime('2020-09-08 18:31:04'));
        $blogPost->setAuthor('Maarad Walid');
        $blogPost->setContent('Post Text !');
        $blogPost->setSlug('a-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('this is another post');
        $blogPost->setPublished(new \DateTime('2020-09-08 18:34:04'));
        $blogPost->setAuthor('Maarad Walid');
        $blogPost->setContent('Post Text !');
        $blogPost->setSlug('another-post');
    }
}
