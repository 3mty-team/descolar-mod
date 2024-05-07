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
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $userOpenReports = sizeof($entityManager->getRepository(UserReport::class)->findOpenReports());
        $postOpenReports = sizeof($entityManager->getRepository(PostReport::class)->findOpenReports());
        $messageOpenReports = sizeof($entityManager->getRepository(MessageReport::class)->findOpenReports());
        $unbanOpenRequests = sizeof($entityManager->getRepository(UnbanRequest::class)->findOpenRequests());

        $reportsCount =
            sizeof($entityManager->getRepository(UserReport::class)->findAll())
            + sizeof($entityManager->getRepository(PostReport::class)->findAll())
            + sizeof($entityManager->getRepository(MessageReport::class)->findAll());

        $openReportsCount = $reportsCount - $userOpenReports - $postOpenReports - $messageOpenReports;

        $xLabels = array(
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai"
        );

        return $this->render('index.html.twig', [
            'username' => $session->get('username'),
            'urCount' => $userOpenReports,
            'prCount' => $postOpenReports,
            'mrCount' => $messageOpenReports,
            'ubrCount' => $unbanOpenRequests,
            'xLabels' => $xLabels,
            "totalReports" => $reportsCount,
            "totalClosedReports" => $openReportsCount
        ]);
    }
}