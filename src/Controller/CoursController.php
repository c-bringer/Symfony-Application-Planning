<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Matiere;
use App\Form\CoursFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    #[Route('/dashboard/cours/creer', name: 'creer_cours')]
    public function creerCours(Request $request): Response {
        $cours = new Cours();
        $form = $this->createForm(CoursFormType::class, $cours);

//        if($request->isXmlHttpRequest()) {
//            return $this->render('dashboard/secretaire/cours/creercours.html.twig', [
//                'coursForm' => $form->createView()
//            ]);
//        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formCours = $form->getData();

            dd($form->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formCours);
            $entityManager->flush();
            return $this->redirectToRoute('new_cours_success');
//            return $this->render('dashboard/secretaire/cours/creercours.html.twig', [
//                'coursForm' => $form->createView()
//            ]);
        }

        return $this->render('dashboard/secretaire/cours/creercours.html.twig', [
            'coursForm' => $form->createView()
        ]);
    }
}