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

    #[Route('/dashboard/calendrier', name: 'calendrier')]
    public function calendar(): Response {
        return $this->render('dashboard/secretaire/calendrier/calendrier.html.twig');
    }

    #[Route('/dashboard/calendrier-etudiant', name: 'calendrier-etudiant')]
    public function calendrierEtudiant(): Response {
        return $this->render('dashboard/etudiant/calendrier/calendriercours.html.twig');
    }
}
