<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index()
    {
        return $this->render("index.html.twig");
    }

    #[Route("/dashboard", name: "dashboard")]
    public function redirectToDashboard()
    {
        return $this->render("dashboard/index.html.twig");
    }
}