<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EtudiantController extends AbstractController
{
    #[Route('/dashboard/etudiants', name: 'liste_etudiants')]
    public function listeEtudiants() {
        $etudiants = $this->getDoctrine()
            ->getRepository(Etudiant::class)
            ->findAll();

        return $this->render('dashboard/secretaire/etudiant/listeetudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    #[Route('/dashboard/etudiant/creer', name: 'creer_etudiant')]
    public function creerIntervenant(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantFormType::class, $etudiant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formEtudiant = $form->getData();

            $password = $passwordEncoder->encodePassword($etudiant, $etudiant->getPassword());
            $etudiant->setPassword($password);
            $etudiant->setRoles(array("ROLE_ETUDIANT"));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEtudiant);
            $entityManager->flush();
            return $this->redirectToRoute('new_etudiant_success');
        }

        return $this->render('dashboard/secretaire/etudiant/creeretudiant.html.twig', [
            'etudiantForm' => $form->createView()
        ]);
    }

    #[Route('/dashboard/etudiant/supprimer/{id}', name: 'supprimer_etudiant')]
    public function supprimerEtudiant($id) {
        $etudiant = $this->getDoctrine()
            ->getRepository(Etudiant::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($etudiant);
        $entityManager->flush();

        return $this->redirectToRoute('remove_etudiant_success');
    }

    #[Route('/dashboard/etudiant/modifier/{id}', name: 'modifier_etudiant')]
    public function modifierEtudiant(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id) {
        $etudiant = $this->getDoctrine()
            ->getRepository(Etudiant::class)
            ->find($id);

        $form = $this->createForm(EtudiantFormType::class, $etudiant);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formEtudiant = $form->getData();

            $password = $passwordEncoder->encodePassword($etudiant, $etudiant->getPassword());
            $etudiant->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEtudiant);
            $entityManager->flush();
            return $this->redirectToRoute('modify_etudiant_success');
        }

        return $this->render('dashboard/secretaire/etudiant/modifieretudiant.html.twig', [
            'etudiantForm' => $form->createView(),
            'etudiant' => $etudiant
        ]);
    }
}
