<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/', name: 'login')]
    public function index($error = null): Response
    {
        return $this->render('login.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function testConnexion(EntityManagerInterface $entityManager, Request $request): Response
    {
        $username = htmlspecialchars($request->get('username'));
        $password = htmlspecialchars($request->get('password'));

        $userRepository = $entityManager->getRepository(User::class);
        $loginRepository = $entityManager->getRepository(Login::class);

        $user = $userRepository->findUserByUsername($username);

        if (!is_null($user)) {
            $areCredentialsCorrect = $loginRepository->testLogin($user, $password);
            if ($areCredentialsCorrect) {
                $session = $request->getSession();
                $session->set('username', $user->getUsername());

                return $this->redirectToRoute('home');
            }
        }
        return $this->index("Nom d'utilisateur ou mot de passe incorrects !");
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->remove('username');

        return $this->redirectToRoute('login');
    }
}