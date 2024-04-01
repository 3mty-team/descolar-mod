<?php

namespace App\Controller\Report;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostReportController extends AbstractController
{

    #[Route('/report/post', name: 'report_post')]
    public function redirectReportPost(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        return $this->render('report/post-report.html.twig', [
            'username' => $session->get('username')
        ]);
    }
}