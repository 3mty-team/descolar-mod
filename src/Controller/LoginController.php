<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/', name: 'login')]
    public function index($error = null): Response
    {
        session_start();

        return $this->render('login.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error
        ]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function testConnexion(EntityManagerInterface $entityManager): Response
    {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $userRepository = $entityManager->getRepository(User::class);
        $loginRepository = $entityManager->getRepository(Login::class);

        $user = $userRepository->findUserByUsername($username);

        if (!is_null($user)) {
            $areCredentialsCorrect = $loginRepository->testLogin($user, $password);
            if ($areCredentialsCorrect) {
                $_SESSION['username'] = $user->getUsername();
                return $this->redirectToRoute('home');
            }
        }
        return $this->index("Nom d'utilisateur ou mot de passe incorrects !");
    }
}