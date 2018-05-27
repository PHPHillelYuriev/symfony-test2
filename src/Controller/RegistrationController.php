<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        //handle the submit 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //Encode the password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            //save the User
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('posts');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
