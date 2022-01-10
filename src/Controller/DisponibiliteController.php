<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Entity\Intervenant;
use App\Entity\Semaine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DisponibiliteController extends AbstractController
{
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    #[Route('/dashboard/calendrier/disponibilites/ajouter', name: 'ajouter_disponibilite', methods: 'POST')]
    public function ajouterDisponibilite(Request $request) {
        $donnees = json_decode($request->getContent());

        if(isset($donnees->start) && !empty($donnees->start) && isset($donnees->end) && !empty($donnees->end)) {
            $code = 200;
            $intervenant = $this->getDoctrine()
                ->getRepository(Intervenant::class)
                ->find($this->security->getUser()->getId());
            $semaines = $this->getDoctrine()
                ->getRepository(Semaine::class)
                ->findAll();

            foreach($semaines as $semaine) {
                if($semaine->getDateDebut() < new \DateTime($donnees->start) && $semaine->getDateFin() > new \DateTime($donnees->start)) {
                    if($semaine->getLibelle() == "Entreprise") {
                        return new Response('Impossible d\'ajouter une disponibilité durant une semaine d\'entreprise', 401);
                    } else if($semaine->getLibelle() == "Férié") {
                        return new Response('Impossible d\'ajouter une disponibilité durant un jour férié', 401);
                    }
                }
            }

            $disponibilite = new Disponibilite();

            $disponibilite->setDateDebut(new \DateTime($donnees->start));
            $disponibilite->setDateFin(new \DateTime($donnees->end));
            $disponibilite->setFkIntervenant($intervenant);

            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibilite);
            $em->flush();

            return new Response('Ok', $code);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }

    #[Route('/dashboard/calendrier/disponibilites/{id}/modifier', name: 'modifier_disponibilite', methods: 'PUT')]
    public function majDisponibilite(?Disponibilite $disponibilite, Request $request) {
        $donnees = json_decode($request->getContent());

        if(isset($donnees->start) && !empty($donnees->start) && isset($donnees->end) && !empty($donnees->end)) {
            $code = 200;
            $intervenant = $this->getDoctrine()
                ->getRepository(Intervenant::class)
                ->find($this->security->getUser()->getId());
            $semaines = $this->getDoctrine()
                ->getRepository(Semaine::class)
                ->findAll();

            foreach($semaines as $semaine) {
                if($semaine->getDateDebut() < new \DateTime($donnees->start) && $semaine->getDateFin() > new \DateTime($donnees->start)) {
                    if($semaine->getLibelle() == "Entreprise") {
                        return new Response('Impossible d\'ajouter une disponibilité durant une semaine d\'entreprise', 401);
                    } else if($semaine->getLibelle() == "Férié") {
                        return new Response('Impossible d\'ajouter une disponibilité durant un jour férié', 401);
                    }
                }
            }

            if(!$disponibilite) {
                $disponibilite = new Disponibilite();
                $code = 201;
            }

            $disponibilite->setDateDebut(new \DateTime($donnees->start));
            $disponibilite->setDateFin(new \DateTime($donnees->end));
            $disponibilite->setFkIntervenant($intervenant);

            $em = $this->getDoctrine()->getManager();
            $em->persist($disponibilite);
            $em->flush();

            return new Response('Ok', $code);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }

    #[Route('/dashboard/calendrier/disponibilites/{id}/supprimer', name: 'supprimer_disponibilite', methods: 'DELETE')]
    public function supprimerDisponibilite($id) {
        $disponibilite = $this->getDoctrine()
            ->getRepository(Disponibilite::class)
            ->find($id);

        if($disponibilite) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($disponibilite);
            $entityManager->flush();

            return new Response('Ok', 200);
        } else {
            return new Response('Données incomplètes', 404);
        }
    }
}
