<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = new User();
        $formRegister = $this->createForm(UserType::class, $user);
        $formRegister->handleRequest($request);

        if ($formRegister->isSubmitted() && $formRegister->isValid()) {
            // encode the plain password

            $avatarFile = $formRegister->get('avatarfile')->getData();

            if ($avatarFile) {

                $avatarFileName = $fileUploader->upload($avatarFile, 'avatars');
                $user->setAvatar($avatarFileName);
            }    
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formRegister->get('plainPassword')->getData()
                )
            )
            ->setRoles(["ROLE_CUSTOMER"]);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            
            return $this->redirectToRoute('app_front');
        }

        return $this->render('front/index.html.twig', [
            'registrationForm' => $formRegister->createView(),
            'controller_name' => 'FrontController',
        ]);
    }
}
