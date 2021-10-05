<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntervenantController extends AbstractController
{
    #[Route('/intervenant', name: 'intervenant')]
    public function index(): Response
    {
        return $this->render('intervenant/index.html.twig', [
            'controller_name' => 'IntervenantController',
        ]);
    }
}