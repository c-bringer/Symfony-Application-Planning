<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Disponibilite;
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

    #[Route('/dashboard/intervenant/{id}/disponibilites', name: 'disponibilites_intervenant')]
    public function disponibilitesIntervenant($id) {
        $intervenant = $this->getDoctrine()
            ->getRepository(Intervenant::class)
            ->find($id);

        return $this->render('dashboard/secretaire/intervenant/disponibilitesintervenant.html.twig', [
            'nomIntervenant' => $intervenant->getNom() . " " . $intervenant->getPrenom(),
            'userId' => $intervenant->getId()
        ]);
    }

    #[Route('/dashboard/intervenant/{id}/recapitulatif', name: 'recapitulatif_intervenant')]
    public function recapitulatifIntervenant($id) {
        $disponibilites = $this->getDoctrine()
            ->getRepository(Disponibilite::class)
            ->createQueryBuilder('disponibilite')
//            ->where('disponibilite.dateDebut BETWEEN :start and :end OR disponibilite.dateFin BETWEEN :start and :end')
            ->join('disponibilite.fkIntervenant', 'user')
            ->addSelect('user')
            ->andWhere('user.id = :user_id')
//            ->setParameter('start', $start->format('Y-m-d H:i:s'))
//            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('user_id', $id)
            ->getQuery()
            ->getResult();

        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->createQueryBuilder('cours')
            ->join('cours.fkIntervenant', 'user')
            ->addSelect('user')
            ->andWhere('user.id = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/secretaire/intervenant/recapitulatifintervenant.html.twig', [
            'disponibilites' => $disponibilites,
            'cours' => $cours
        ]);
    }
}
