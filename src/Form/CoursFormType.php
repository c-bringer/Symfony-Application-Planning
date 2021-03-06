<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Groupe;
use App\Entity\Intervenant;
use App\Entity\Matiere;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commenceA', DateTimeType::class)
            ->add('finiA', DateTimeType::class)
            ->add('libelle', TextType::class, [
                'required' => true
            ])
            ->add('fkIntervenant', EntityType::class, [
                'class' => Intervenant::class,
                'choice_label' => function ($intervernant) {
                    return $intervernant->getNom() . ' ' . $intervernant->getPrenom();
                },
                'multiple' => false,
                'placeholder' => 'Sélectionner un intervenant',
                'required' => true])
            ->add('fkMatiere', EntityType::class, [
                    'class' => Matiere::class,
                    'placeholder' => 'Sélectionner une matière',
                    'multiple' => false,
                    'required' => true,
                    'choice_label' => function ($matiere) {
                        return $matiere->getLibelle();
                    },
                ])
            ->add('fkGroupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'required' => false])
            ->add('enregistrer', SubmitType::class);

//        $builder->get('fkIntervenant')->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//                $data = $event->getData();
//
//                $form->add('fkMatiere', EntityType::class, [
//                    'class' => Matiere::class,
//                    'placeholder' => 'Sélectionner une matière',
//                    'multiple' => false,
//                    'required' => true,
//                    'choices' => $data->getMatieres()
//                ]);
//            }
//        );

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $form = $event->getForm();
//            $data = $event->getData();
//            $matieres = $data->getFkMatiere();
//
//            dump($data);
//
//            if ($matieres) {
//                $form->get('fkIntervenant')->setData($matieres->getLibelle());
//
//                $form->add('fkMatiere', EntityType::class, [
//                    'class' => Matiere::class,
//                    'placeholder' => 'Sélectionner une matière',
//                    'choice_label' => 'libelle',
//                    'multiple' => false,
//                    'required' => true,
//                    'choices' => $matieres->getLibelle()
//                ]);
//            } else {
//                $form->add('fkMatiere', EntityType::class, [
//                    'class' => Matiere::class,
//                    'placeholder' => 'Sélectionner une matière',
//                    'choice_label' => 'libelle',
//                    'multiple' => false,
//                    'required' => false
//                ]);
//            }
//        }
//        );


//        $formModifier = function (FormInterface $form, Intervenant $intervenant = null) {
//            $matieres = null === $intervenant ? [] : $intervenant->getMatieres();
//
//            $form->add('fkMatiere', EntityType::class, [
//                    'class' => Matiere::class,
//                    'placeholder' => 'Sélectionner une matière',
//                    'choice_label' => 'libelle',
//                    'multiple' => false,
//                    'required' => true,
//                    'choices' => $matieres
//                ]);
//        };
//
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//                $data = $event->getData();
//
//                $formModifier($event->getForm(), $data->getFkIntervenant());
//            }
//        );
//
//        $builder->get('fkIntervenant')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier) {
//                // It's important here to fetch $event->getForm()->getData(), as
//                // $event->getData() will get you the client data (that is, the ID)
//                $intervenant = $event->getForm()->getData();
//
//                // since we've added the listener to the child, we'll have to pass on
//                // the parent to the callback functions!
//                $formModifier($event->getForm()->getParent(), $intervenant);
//            }
//        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
