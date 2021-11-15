<?php

namespace App\Controller;

use App\Entity\Semaine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SemaineController extends AbstractController
{
    #[Route('/dashboard/parametre/calendrier/semaine-et-jour-ferie/ajouter', name: 'ajouter_semaine', methods: 'POST')]
    public function ajouterSemaine(Request $request) {
        $donnees = json_decode($request->getContent());

        if(isset($donnees->title) && !empty($donnees->title)
            && isset($donnees->start) && !empty($donnees->start)
            && isset($donnees->end) && !empty($donnees->end)) {
            $code = 200;
            $semaine = new Semaine();

            $semaine->setLibelle($donnees->title);
            $semaine->setDateDebut(new \DateTime($donnees->start));
            $semaine->setDateFin(new \DateTime($donnees->end));

            $em = $this->getDoctrine()->getManager();
            $em->persist($semaine);
            $em->flush();

            return new Response('Ok', $code);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }

    #[Route('/dashboard/parametre/calendrier/semaine-et-jour-ferie/{id}/supprimer', name: 'supprimer_semaine', methods: 'DELETE')]
    public function supprimerSemaine($id) {
        $semaine = $this->getDoctrine()
            ->getRepository(Semaine::class)
            ->find($id);

        if($semaine) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($semaine);
            $entityManager->flush();

            return new Response('Ok', 200);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }
}
