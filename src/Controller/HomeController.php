<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        session_start();

        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
            'username' => $_SESSION['username']
        ]);
    }
}