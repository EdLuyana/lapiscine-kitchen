<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route(path:'/admin/logout', name:'admin_logout')]
    public function logout() : void
    {
        //route utilisée par symfony pour se décnnecter, c'est magique un peu
        //en vrai ça renvoi vers le fichier security.yalm et est utilisé dans le logout
    }

    #[Route(path:'/admin/users/list', name:'admin_list_users', methods: ['GET'])]
    public function listUsers(UserRepository $userRepository) : Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users/list.html.twig', ['users'=>$users]);
    }

    #[Route('admin/create/user', name: 'admin_create_user')]
    public function createUser(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted()) {
            $password = $userForm->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_users');
        }
        return $this->render('admin/users/create.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

}