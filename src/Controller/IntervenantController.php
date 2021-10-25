<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Form\IntervenantFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    #[Route('/dashboard/intervenant/modifier/{id}', name: 'modifier_intervenant')]
    public function modifierIntervenant(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id) {
        $intervenant = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->find($id);

        $form = $this->createForm(IntervenantFormType::class, $intervenant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formIntervenant = $form->getData();

            $password = $passwordEncoder->encodePassword($intervenant, $intervenant->getPassword());
            $intervenant->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formIntervenant);
            $entityManager->flush();
            return $this->redirectToRoute('modify_intervenant_success');
        }

        return $this->render('dashboard/secretaire/intervenant/modifierintervenant.html.twig', [
            'intervenantForm' => $form->createView(),
            'intervenant' => $intervenant
        ]);
    }

    #[Route('/dashboard/intervenant/supprimer/{id}', name: 'supprimer_intervenant')]
    public function supprimerIntervenant($id) {
        $intervenant = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($intervenant);
        $entityManager->flush();

        return $this->redirectToRoute('remove_intervenant_success');
    }

    #[Route('/dashboard/intervenant/creer', name: 'creer_intervenant')]
    public function creerIntervenant(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $intervenant = new Intervenant();
        $form = $this->createForm(IntervenantFormType::class, $intervenant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formIntervenant = $form->getData();

            $password = $passwordEncoder->encodePassword($intervenant, $intervenant->getPassword());
            $intervenant->setPassword($password);
            $intervenant->setRoles(array("ROLE_INTERVENANT"));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formIntervenant);
            $entityManager->flush();
            return $this->redirectToRoute('new_intervenant_success');
        }

        return $this->render('dashboard/secretaire/intervenant/creerintervenant.html.twig', [
            'intervenantForm' => $form->createView()
        ]);
    }
}
