<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\Semaine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report/planningannuel', name: 'report_planning_annuel')]
    public function index(): Response
    {
        $calendrier = $this->getDoctrine()
            ->getRepository(Calendrier::class)
            ->find(1);

        $semaines = $this->getDoctrine()
            ->getRepository(Semaine::class)
            ->findAll();

        return $this->render('report/planningannuel.html.twig', [
            'calendrier' => $calendrier,
            'semaines' => $semaines,
        ]);
    }
}
