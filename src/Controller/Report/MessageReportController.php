<?php

namespace App\Controller\Report;

use App\Entity\Report\MessageReport;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageReportController extends AbstractController
{
    #[Route('/report/message', name: 'report_message')]
    public function redirectReportMessage(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $messageReportRepository = $entityManager->getRepository(MessageReport::class);
        $messageReportRepository->populateDB($entityManager);

        $openReports = $messageReportRepository->findOpenReports();

        return $this->render('report/message-report.html.twig', [
            'username' => $session->get('username'),
            'openReports' => $openReports
        ]);
    }

    #[Route('/ban/message/{id}', name: 'ban_message_report')]
    public function banUser(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(MessageReport::class)->closeReport($id, $admin, 0, 0, 1);

        return $this->redirectToRoute('report_message');
    }

    #[Route('/delete/message/{id}', name: 'delete_message_report')]
    public function deleteMessage(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(MessageReport::class)->closeReport($id, $admin, 0, 1, 0);

        return $this->redirectToRoute('report_message');
    }

    #[Route('/ignore/message/{id}', name: 'ignore_message_report')]
    public function ignoreUser(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(MessageReport::class)->closeReport($id, $admin, 1, 0, 0);

        return $this->redirectToRoute('report_message');
    }
}