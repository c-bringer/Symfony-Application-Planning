<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    #[Route('/dashboard/calendrier/disponibilites', name: 'calendrier_disponibilite')]
    public function calendrierDisponibilites() {
        return $this->render('dashboard/intervenant/calendrier/calendrierdisponibilites.html.twig');
    }
}
