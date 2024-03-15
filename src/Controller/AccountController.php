<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/accounts', name: 'accounts')]
    public function index(): Response
    {
        return $this->render('accounts.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}