<?php

namespace App\Controller\Report;

use App\Entity\Report\UserReport;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserReportController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    #[Route('/report/user', name: 'report_user')]
    public function redirectReportUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $userReportRepository = $entityManager->getRepository(UserReport::class);
        $userReportRepository->populateDB($entityManager);

        $openReports = $userReportRepository->findOpenReports();

        return $this->render('report/user-report.html.twig', [
            'username' => $session->get('username'),
            'openReports' => $openReports
        ]);
    }

    #[Route('/ban/user/{id}', name: 'ban_user_report')]
    public function banUser(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(UserReport::class)->closeReport($id, $admin, 1);

        return $this->redirectToRoute('report_user');
    }

    #[Route('/ignore/user/{id}', name: 'ignore_user_report')]
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