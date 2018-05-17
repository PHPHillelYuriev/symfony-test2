<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Posts;
use App\Entity\Category;
use App\Entity\Tags;

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
    public function posts()
    {   
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        
        return $this->render('main/posts.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/posts/{postId}", name="showPostById", requirements={"postId"="\d+"})
     */
    public function showPostById($postId)
    {   
        $post = $this->getDoctrine()->getRepository(Posts::class)->find($postId);
        
        return $this->render('main/post.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/posts/{tag}", name="tag")
     */
    public function tag($tag)
    {   
        $tag = $this->getDoctrine()->getRepository(Tags::class)->findOneBy(['name' => $tag]);
        
        return $this->render('main/tag.html.twig', [
            'tag' => $tag,
        ]);
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
