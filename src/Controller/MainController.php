<?php

namespace App\Controller;

// use Symfony\Component\Routing\Annotation\Route;
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
     * @ParamConverter("post", class="App\Entity\Posts")
     */
    public function showPostById(Posts $post)
    {   
        return $this->render('main/post.html.twig', ['post' => $post]);
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
     * @Route("/posts/tags/{tagName}", name="showPostsByTag")
     * @ParamConverter("posts", options={"mapping": {"tagName": "name"}})
     */
    public function showPostsByTag(TagsRepository $tagRepository)
    {   
        // $tag = $this->getDoctrine()->getRepository(Tags::class)->findOneBy(['name' => $tag]);
        $tags = $tagsRepositoryfindOneBy(['name' => 'tagName']);
        
        return $this->render('main/tag.html.twig', ['tag' => $posts->findOneBy(['name' => 'tagName']) ]);
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
