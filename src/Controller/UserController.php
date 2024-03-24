<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/create-user', name: 'create_user')]
    public function createUser(EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setUsername("test123" . (String) rand(0,999999999999999999));
        $user->setFirstName("Te");
        $user->setLastName("St");
        date_default_timezone_set('Europe/Paris');
        $user->setCreationDate(new DateTime());
        $user->setIsActive(1);

        $entityManager->persist($user);
        $entityManager->flush();

        $message = "Saved new user :\n" . $user->toString();

        return $this->render('user/createUser.html.twig', [
            'controller_name' => 'UserController (Create user)',
            'message' => $message,
        ]);
    }

}
