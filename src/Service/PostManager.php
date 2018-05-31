<?php
/**
 * Created by PhpStorm.
 * User: Constructor
 * Date: 29.05.2018
 * Time: 13:42
 */

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Posts;
use App\Form\PostsType;

class PostManager
{
    private $formFactory;
    private $entityManager;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function createPost(Request $request)
    {
        $post = new Posts();
        
        return $this->editPost($request, $post);    
    }

    public function editPost(Request $request, Posts $post)
    {
        $form = $this->formFactory->create(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->save($post);

            return;
        }

        return [
            'post' => $post,
            'form' => $form->createView(),
        ];
    }

    private function save(Post $post)
    {
        if (!$post->getId()) {
            $this->entityManager->flush();
        }
    }

}