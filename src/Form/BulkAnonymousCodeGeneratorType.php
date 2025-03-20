<?php

namespace App\Form;

use App\Entity\Exam;
use App\Entity\Level;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BulkAnonymousCodeGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exam', EntityType::class, [
                'class' => Exam::class,
                'choice_label' => function (Exam $exam) {
                    return $exam->getEc()->getCode() . ' - ' . $exam->getEc()->getName() . ' (' . $exam->getExamDate()->format('d/m/Y') . ')';
                },
                'placeholder' => 'Choisir un examen',
                'label' => 'Examen',
                'required' => true,
                'choices' => $options['exams'] ?? [],
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un niveau',
                'label' => 'Niveau',
                'required' => true,
                'choices' => $options['levels'] ?? [],
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('codeLength', IntegerType::class, [
                'label' => 'Longueur du code',
                'required' => true,
                'data' => 6, // Valeur par défaut
                'attr' => [
                    'min' => 4,
                    'max' => 10
                ],
                'mapped' => false,
            ])
            ->add('codeType', ChoiceType::class, [
                'label' => 'Type de code',
                'choices' => [
                    'Alphanumérique' => 'ALPHANUMERIC',
                    'Numérique uniquement' => 'NUMERIC',
                    'Alphabétique uniquement' => 'ALPHABETIC'
                ],
                'required' => true,
                'data' => 'ALPHANUMERIC', // Valeur par défaut
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'exams' => [],
            'levels' => [],
        ]);
    }
}