<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    #[Route('/dashboard/calendrier', name: 'calendrier')]
    public function calendar(): Response {
        return $this->render('dashboard/secretaire/calendrier/calendrier.html.twig');
    }
}