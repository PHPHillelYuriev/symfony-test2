<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Posts;
use App\Entity\Category;
use App\Entity\Tags;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CategoryRepository;
use App\Repository\TagsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Psr\Log\LoggerInterface;
use App\Service\PostManager;

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
    public function showPostById(Posts $post, Request $request, LoggerInterface $logger)
    {
        $comment = new Comments($post);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //add new comment to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            //show flash message
            $message = 'You add a new comment!';
            $this->addFlash('success', $message);

            //create log message
            $logger->info($message);

            return $this->redirectToRoute('showPostById', ['id' => $post->getId()] );
        }

        return $this->render('main/post.html.twig', [
            'post' => $post,
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/posts/{id}/heart", name="togglePostHeart", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function togglePostHeart()
    {
        return $this->json(['hearts' => rand(5, 100)]);
    }

    /**
     * @Route("{id}/comments/{commentId}/delete", name="deleteComment")
     * @ParamConverter("comment", options={"mapping": {"commentId" = "id"}})
     */
    public function delete(Request $request, Comments $comment, Posts $post, LoggerInterface $logger)
    {
        //remove comment from database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        //show flash message
        $message = 'You delete a comment!';
        $this->addFlash('success', $message);

        //create log message
        $logger->info($message);

        return $this->redirectToRoute('showPostById', ['id' => $post->getId()] );
    }

    /**
     * @Route("/posts/tags/{tag}", name="showPostsByTag")
     * @ParamConverter("tag", options={"mapping": {"tag" = "name"}})
     */
    public function showPostsByTag(Tags $tag)
    {   
        return $this->render('main/tag.html.twig', ['tag' => $tag ]);
    }

    /**
     * @Route("/posts/categories/showCategories", name="showCategoriesFromAjax", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function showCategoriesFromAjax()
    {
        $categories = [
            'category1' => 'Space',
            'category2' => 'Planet',
            'category3' => 'Our planet',
        ];

        return $this->json($categories);
    }

    public function tags(TagsRepository $repository)
    {
        $tags = $repository->findAll();

        return $this->render('main/partial/tags.html.twig', compact('tags'));       
    }  

}

