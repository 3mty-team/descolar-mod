<?php

namespace App\Controller;

use App\Entity\Report\MessageReport;
use App\Entity\Report\PostReport;
use App\Entity\Report\UserReport;
use App\Entity\UnbanRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $userReportsCount = sizeof($entityManager->getRepository(UserReport::class)->findOpenReports());
        $postReportsCount = sizeof($entityManager->getRepository(PostReport::class)->findOpenReports());
        $messageReportsCount = sizeof($entityManager->getRepository(MessageReport::class)->findOpenReports());
        $unbanRequestsCount = sizeof($entityManager->getRepository(UnbanRequest::class)->findOpenRequests());

        return $this->render('index.html.twig', [
            'username' => $session->get('username'),
            'urCount' => $userReportsCount,
            'prCount' => $postReportsCount,
            'mrCount' => $messageReportsCount,
            'ubrCount' => $unbanRequestsCount,
        ]);
    }
}