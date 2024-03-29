<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report/user', name: 'report_user')]
    public function redirectReportUser(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user-report.html.twig', [
            'username' => $session->get('username')
        ]);
    }

    #[Route('/report/post', name: 'report_post')]
    public function redirectReportPost(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        return $this->render('post-report.html.twig', [
            'username' => $session->get('username')
        ]);
    }

    #[Route('/report/message', name: 'report_message')]
    public function redirectReportMessage(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        return $this->render('message-report.html.twig', [
            'username' => $session->get('username')
        ]);
    }
}