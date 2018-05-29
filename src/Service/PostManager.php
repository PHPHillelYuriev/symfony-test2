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
        $form = $this->formFactory->create(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return ;
//            $this->redirectToRoute('adminPanel');
        }
    }

}