<?php

namespace App\Controller;

use App\Entity\Matiere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MatiereController extends AbstractController
{
    #[Route('/dashboard/matieres', name: 'liste_matieres')]
    public function listeMatieres()
    {
        $matieres = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->findAll();

        return $this->render('dashboard/secretaire/matiere/listematieres.html.twig', [
            'matieres' => $matieres,
        ]);
    }
}
