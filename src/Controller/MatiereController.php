<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/dashboard/matiere/supprimer/{id}', name: 'supprimer_matiere')]
    public function supprimerMatiere($id) {
        $matiere = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($matiere);
        $entityManager->flush();

        return $this->redirectToRoute('remove_matiere_success');
    }

    #[Route('/dashboard/matiere/modifier/{id}', name: 'modifier_matiere')]
    public function modifierIntervenant(Request $request, $id) {
        $matiere = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($id);

        $form = $this->createForm(MatiereFormType::class, $matiere);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formMatiere = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formMatiere);
            $entityManager->flush();
            return $this->redirectToRoute('modify_matiere_success');
        }

        return $this->render('dashboard/secretaire/matiere/modifiermatiere.html.twig', [
            'matiereForm' => $form->createView(),
            'matiere' => $matiere
        ]);
    }

    #[Route('/dashboard/matiere/creer', name: 'creer_matiere')]
    public function creerMatiere(Request $request) {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereFormType::class, $matiere);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formMatiere = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formMatiere);
            $entityManager->flush();
            return $this->redirectToRoute('new_matiere_success');
        }

        return $this->render('dashboard/secretaire/matiere/creermatiere.html.twig', [
            'matiereForm' => $form->createView()
        ]);
    }
}
