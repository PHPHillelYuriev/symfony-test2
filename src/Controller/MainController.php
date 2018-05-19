<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Posts;
use App\Entity\Category;
use App\Entity\Tags;
use App\Repository\CategoryRepository;
use App\Repository\TagsRepository;

class MainController extends Controller
{
    /**
     * @Route("/", name="homapage")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function posts(CategoryRepository $category)
    {   
        return $this->render('main/posts.html.twig', ['categories' => $category->findAll()]);
    }

    /**
     * @Route("/posts/{id}", name="showPostById", requirements={"id"="\d+"})
     * 
     */
    public function showPostById(Posts $post)
    {   
        return $this->render('main/post.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/posts/tags", name="showTags")
     */
    public function showTags(TagsRepository $tags)
    {   
        return $this->render('main/tags.html.twig', ['tags' => $tags->findAll()]);
    }

    /**
     * @Route("/posts/tags/{tagName}", name="showPostsByTag")
     */
    public function showPostsByTag(TagsRepository $tag, string $tagName)
    {   
        // $tag = $this->getDoctrine()->getRepository(Tags::class)->findOneBy(['name' => $tag]);

        return $this->render('main/tag.html.twig', ['tag' => $tag->findOneBy(['name' => $tagName]) ]);
    }

    /**
     * @Route("/{url}", name="remove_trailing_slash",
     *     requirements={"url" = ".*\/$"})
     */
     public function removeTrailingSlash(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();
        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
        return $this->redirect($url, 308);
    }

    
}
