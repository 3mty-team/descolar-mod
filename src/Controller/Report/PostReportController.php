<?php

namespace App\Controller\Report;

use App\Entity\Report\PostReport;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostReportController extends AbstractController
{

    #[Route('/report/post', name: 'report_post')]
    public function redirectReportPost(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        $postReportRepository = $entityManager->getRepository(PostReport::class)->findOpenReports();

        return $this->render('report/post-report.html.twig', [
            'username' => $session->get('username'),
            'postReportRepository' => $postReportRepository
        ]);
    }

    #[Route('/ban/post/{id}', name: 'ban_post_report')]
    public function banUser(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(PostReport::class)->closeReport($id, $admin, 0, 0, 1);

        return $this->redirectToRoute('report_post');
    }

    #[Route('/delete/post/{id}', name: 'delete_post_report')]
    public function deletePost(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(PostReport::class)->closeReport($id, $admin, 0, 1, 0);

        return $this->redirectToRoute('report_post');
    }

    #[Route('/ignore/post/{id}', name: 'ignore_post_report')]
    public function ignoreReport(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        // Get current logged user
        $admin = $entityManager->getRepository(User::class)->findOneBy(
            array('username' => $request->getSession()->get('username'))
        );

        $entityManager->getRepository(PostReport::class)->closeReport($id, $admin, 1, 0, 0);

        return $this->redirectToRoute('report_post');
    }
}