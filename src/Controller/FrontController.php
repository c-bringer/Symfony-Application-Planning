<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index()
    {
        return $this->render("index.html.twig");
    }

    #[Route("/login-success", name: "login_success")]
    public function redirectAfterLoginSuccess()
    {

    }
}