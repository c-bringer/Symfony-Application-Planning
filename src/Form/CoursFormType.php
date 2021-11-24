<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Groupe;
use App\Entity\Intervenant;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commenceA', DateTimeType::class)
            ->add('finiA', DateTimeType::class)
            ->add('libelle', TextType::class, [
                'required' => false
            ])
            ->add('fkIntervenant', EntityType::class, [
                'class' => Intervenant::class,
                'choice_label' => function ($intervernant) {
                    return $intervernant->getNom() . ' ' . $intervernant->getPrenom();
                },
                'multiple' => false,
                'mapped' => false,
                'placeholder' => 'Sélectionner un intervenant',
                'required' => true])
            ->add('fkGroupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'required' => false])
            ->add('enregistrer', SubmitType::class);

        $builder->get('fkIntervenant')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                $form->getParent()->add('fkMatiere', EntityType::class, [
                    'class' => Matiere::class,
                    'placeholder' => 'Sélectionner une matière',
                    'choice_label' => 'libelle',
                    'multiple' => false,
                    'required' => true,
                    'choices' => $form->getData()->getMatieres()
                ]);
            }
        );

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $matieres = $data->getFkMatiere();

            if ($matieres) {
                $form->get('fkIntervenant')->setData($matieres->getLibelle());

                $form->add('fkMatiere', EntityType::class, [
                    'class' => Matiere::class,
                    'placeholder' => 'Sélectionner une matière',
                    'choice_label' => 'libelle',
                    'multiple' => false,
                    'required' => true,
                    'choices' => $matieres->getLibelle()
                ]);
            } else {
                $form->add('fkMatiere', EntityType::class, [
                    'class' => Matiere::class,
                    'placeholder' => 'Sélectionner une matière',
                    'choice_label' => 'libelle',
                    'multiple' => false,
                    'required' => false,
                    'choices' => []
                ]);
            }
        }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
