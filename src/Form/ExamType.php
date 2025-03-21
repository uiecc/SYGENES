<?php

namespace App\Form;

use App\Entity\AcademicYear;
use App\Entity\EC;
use App\Entity\Exam;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ec', EntityType::class, [
                'class' => EC::class,
                'choices' => $options['ecs'] ?? [],
                'choice_label' => function (EC $ec) {
                    return $ec->getCode() . ' - ' . $ec->getName();
                },
                'placeholder' => 'Choisir un EC',
                'label' => 'Élément Constitutif',
                'required' => true,
            ])
            ->add('examDate', DateTimeType::class, [
                'label' => 'Date et heure de l\'examen',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type d\'examen',
                'choices' => [
                    'Normal' => 'NORMAL',
                    'Rattrapage' => 'RATTRAPAGE',
                ],
                'required' => true,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Planifié' => 'PLANNED',
                    'En cours' => 'IN_PROGRESS',
                    'Terminé' => 'COMPLETED',
                    'Corrigé' => 'GRADED',
                ],
                'required' => true,
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Coefficient',
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'readonly' => true, // Rendre le champ en lecture seule car il sera défini automatiquement
                ],
                'help' => 'Coefficient automatiquement calculé selon les crédits de l\'EC',
            ])
            ->add('academicYear', EntityType::class, [
                'class' => AcademicYear::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une année académique',
                'label' => 'Année académique',
                'required' => true,
            ]);
            
        // Ajouter le champ originalExam seulement pour les examens de type rattrapage
        if ($options['show_original_exam']) {
            $builder->add('originalExam', EntityType::class, [
                'class' => Exam::class,
                'choice_label' => function (Exam $exam) {
                    return $exam->getEc()->getCode() . ' - ' . $exam->getExamDate()->format('d/m/Y');
                },
                'placeholder' => 'Choisir l\'examen initial',
                'label' => 'Examen initial (pour rattrapage)',
                'required' => false,
                'choices' => $options['original_exams'] ?? [],
            ]);
        }
        
        // Écouter les événements de formulaire pour mettre à jour le poids en fonction de l'EC sélectionné
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $exam = $event->getData();
            if ($exam && $exam->getEc()) {
                // Si l'EC est déjà défini, définir le poids basé sur les crédits de l'EC
                $exam->setWeight($exam->getEc()->getCredit());
            }
        });
        
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $exam = $event->getData();
            if ($exam && $exam->getEc()) {
                // Mettre à jour le poids avec la valeur des crédits de l'EC après soumission
                $exam->setWeight($exam->getEc()->getCredit());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
            'ecs' => [],
            'show_original_exam' => false,
            'original_exams' => [],
        ]);
    }
}