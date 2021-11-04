<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Form\CalendrierFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParametreController extends AbstractController
{
    #[Route('/dashboard/parametre/calendrier', name: 'parametre_calendrier')]
    public function parametrerCalendrier(Request $request): Response
    {
        $calendrier = $this->getDoctrine()
            ->getRepository(Calendrier::class)
            ->find(1);

        $form = $this->createForm(CalendrierFormType::class, $calendrier);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formCalendrier = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formCalendrier);
            $entityManager->flush();
            return $this->redirectToRoute('modify_calendar_success');
        }

        return $this->render('dashboard/secretaire/parametre/calendrier.html.twig', [
            'calendrierForm' => $form->createView(),
            'calendrier' => $calendrier
        ]);
    }
}
