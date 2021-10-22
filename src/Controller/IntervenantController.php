<?php

namespace App\Controller;

use App\Entity\Intervenant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IntervenantController extends AbstractController
{
    #[Route('/dashboard/intervenants', name: 'liste_intervenants')]
    public function listeIntervenants() {
        $intervenants = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->findAll();

        return $this->render('dashboard/secretaire/intervenant/listeintervenant.html.twig', [
           'intervenants' => $intervenants
        ]);
    }
}
