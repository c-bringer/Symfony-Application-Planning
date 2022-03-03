<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Disponibilite;
use App\Form\CoursFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    #[Route('/dashboard/cours/creer', name: 'creer_cours')]
    public function creerCours(Request $request): Response {
        $cours = new Cours();
        $form = $this->createForm(CoursFormType::class, $cours);
        $coursForm = [
            'coursForm' => $form->createView(),
            'erreur' => ''
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formCours = $form->getData();

            if($this->checkIntervenantMatiere($formCours)) {
                $coursForm['erreur'] = "La matière n'est pas enséigné par l'intervenant";
                return $this->render('dashboard/secretaire/cours/creercours.html.twig', $coursForm);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formCours);
            $entityManager->flush();
            return $this->redirectToRoute('new_cours_success');
        }

        return $this->render('dashboard/secretaire/cours/creercours.html.twig', $coursForm);
    }

    #[Route('/dashboard/cours/creer/disponibilite/{id}', name: 'creer_cours_depuis_disponibilite')]
    public function creerCoursDepuisDisponibilite(Request $request, $id) {
        $cours = new Cours();
        $disponibilite = $this->getDoctrine()
            ->getRepository(Disponibilite::class)
            ->find($id);

        $cours->setCommenceA($disponibilite->getDateDebut());
        $cours->setFiniA($disponibilite->getDateFin());
        $cours->setFkIntervenant($disponibilite->getFkIntervenant());

        $form = $this->createForm(CoursFormType::class, $cours);
        $coursForm = [
            'coursForm' => $form->createView(),
            'erreur' => ''
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formCours = $form->getData();

            if($this->checkIntervenantMatiere($formCours)) {
                $coursForm['erreur'] = "La matière n'est pas enséigné par l'intervenant";
                return $this->render('dashboard/secretaire/cours/creercours.html.twig', $coursForm);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formCours);
            $entityManager->flush();
            return $this->redirectToRoute('new_cours_success');
        }

        return $this->render('dashboard/secretaire/cours/creercours.html.twig', $coursForm);
    }

    private function checkIntervenantMatiere($formCours) {
        $intervenant = $formCours->getFkIntervenant();
        $matiere = $formCours->getFkMatiere();

        $matieresIntervenant = $intervenant->getMatieres();
        
        foreach($matieresIntervenant as $item) {
            if($item == $matiere) {
                return false;
            }
        }

        return true;
    }
}