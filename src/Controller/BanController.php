<?php

namespace App\Controller;

use App\Entity\UnbanRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BanController extends AbstractController
{
    #[Route('/ban', name: 'ban')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $unbanRequestRepository = $entityManager->getRepository(UnbanRequest::class)->findOpenRequests();

        return $this->render('ban.html.twig', [
            'username' => $request->getSession()->get('username'),
            'unbanRequestsRepository' => $unbanRequestRepository
        ]);
    }

    #[Route('/accept/unban/{id}', name: 'accept_unban')]
    public function acceptUnban(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(UnbanRequest::class)->closeReport($id, $admin, 1);

        return $this->redirectToRoute('ban');
    }

    #[Route('/ignore/unban/{id}', name: 'ignore_unban')]
    public function ignoreUnban(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(UnbanRequest::class)->closeReport($id, $admin, 0);

        return $this->redirectToRoute('ban');
    }
}