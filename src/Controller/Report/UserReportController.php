<?php

namespace App\Controller\Report;

use App\Entity\User;
use App\Entity\UserReport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserReportController extends AbstractController
{
    #[Route('/report/user', name: 'report_user')]
    public function redirectReportUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $userReportRepository = $entityManager->getRepository(UserReport::class)->findOpenReports();

        return $this->render('report/user-report.html.twig', [
            'username' => $session->get('username'),
            'userReportRepository' => $userReportRepository
        ]);
    }

    #[Route('/ban/user/{id}', name: 'ban_user')]
    public function banUser(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(UserReport::class)->closeReport($id, $admin, 1);

        return $this->redirectToRoute('report_user');
    }

    #[Route('/ignore/{id}', name: 'ban_user')]
    public function ignoreReport(EntityManagerInterface $entityManager, Request $request, int $id)
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(UserReport::class)->closeReport($id, $admin, 0);

        return $this->redirectToRoute('report_user');
    }
}