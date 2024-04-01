<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BanController extends AbstractController
{
    #[Route('/ban', name: 'ban')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->get('username') == null) {
            return $this->redirectToRoute('login');
        }

        return $this->render('ban.html.twig', [
            'username' => $request->getSession()->get('username')
        ]);
    }

    #[Route('/ban/test', name: 'ban_test')]
    public function ouai(): Response
    {
        return $this->render('ban-test.html.twig', [
            'controller_name' => 'ban test là',
            'message' => "ouai"
        ]);
    }
}