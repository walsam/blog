<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [
      [
          'id' => 1,
          'slug' => 'hello-world',
          'title' => 'Hello World!'
      ],
      [
          'id' => 2,
          'slug' => 'another-one',
          'title' => 'This is another Post!'
      ],
      [
          'id' => 3,
          'slug' => 'the-last-one',
          'title' => 'this is the last post!'
      ]
    ];
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 1})
     */
    public function list($page){
        return $this->json(
            [
                'page' => $page,
                'data' => array_map(function ($item){
                    return $this->generateUrl('blog_by_slug', ['slug'=>$item['slug']]);
                },self::POSTS)

            ]
        );
    }

    /**
     * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
     */
    public function post($id){
        return $this->json(
            self::POSTS[array_search($id,array_column(self::POSTS,'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="blog_by_slug")
     */
    public function postBySlug($slug){
        return $this->json(
            self::POSTS[array_search($slug,array_column(self::POSTS,'slug'))]
        );
    }
}