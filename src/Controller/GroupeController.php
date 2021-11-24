<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Form\GroupeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GroupeController extends AbstractController
{
    #[Route('/dashboard/groupes', name: 'liste_groupe')]
    public function listeGroupes() {
        $groupes = $this->getDoctrine()
            ->getRepository(Groupe::class)
            ->findAll();

        return $this->render('dashboard/secretaire/groupe/listegroupe.html.twig', [
            'groupes' => $groupes
        ]);
    }

    #[Route('/dashboard/groupe/creer', name: 'creer_groupe')]
    public function creerGroupe(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $groupe = new Groupe();
        $form = $this->createForm(GroupeFormType::class, $groupe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formGroupe = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formGroupe);
            $entityManager->flush();
            return $this->redirectToRoute('new_groupe_success');
        }

        return $this->render('dashboard/secretaire/groupe/creergroupe.html.twig', [
            'groupeForm' => $form->createView()
        ]);
    }

    #[Route('/dashboard/groupe/supprimer/{id}', name: 'supprimer_groupe')]
    public function supprimerGroupe($id) {
        $groupe = $this->getDoctrine()
            ->getRepository(Groupe::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($groupe);
        $entityManager->flush();

        return $this->redirectToRoute('remove_groupe_success');
    }
}
