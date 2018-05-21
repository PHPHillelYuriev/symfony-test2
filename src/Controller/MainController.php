<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Posts;
use App\Entity\Category;
use App\Entity\Tags;
use App\Repository\CategoryRepository;
use App\Repository\TagsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    public function posts(CategoryRepository $categoryRepository)
    {   
        $categories = $categoryRepository->findAll();

        return $this->render('main/posts.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/posts/{id}", name="showPostById", requirements={"id"="\d+"})
     */
    public function showPostById(Posts $post)
    {   
        return $this->render('main/post.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/posts/{id}/heart", name="togglePostHeart", requirements={"id"="\d+"})
     */
    public function togglePostHeart(string $id)
    {
        return $this->json(['hearts' => rand(5, 100)]);
    }

    /**
     * @Route("/posts/tags", name="showTags")
     */
    public function showTags(TagsRepository $tagsRepository)
    {   
        $tags = $tagsRepository->findAll();

        return $this->render('main/tags.html.twig', ['tags' => $tags]);
    }

    /**
     * @Route("/posts/{category}", name="showCategory")
     */
    public function showCategory(CategoryRepository $categoryRepository, string $category)
    {   
        $category = $categoryRepository->findOneBy(['name' => $category]);

        return $this->render('main/category.html.twig', ['category' => $category]);
    }

    /**
     * @Route("/posts/tags/{tag}", name="showPostsByTag")
     */
    public function showPostsByTag(TagsRepository $tagRepository, string $tag)
    {   
        $tag = $tagRepository->findOneBy(['name' => $tag]);
        
        return $this->render('main/tag.html.twig', ['tag' => $tag ]);
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
