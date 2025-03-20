<?php

namespace App\Form;

use App\Entity\AnonymousCode;
use App\Entity\ExamGrade;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamGradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anonymousCode', EntityType::class, [
                'class' => AnonymousCode::class,
                'choice_label' => 'code',
                'placeholder' => 'Choisir un code anonyme',
                'label' => 'Code anonyme',
                'required' => true,
                'choices' => $options['anonymous_codes'] ?? [],
                'disabled' => $options['is_code_fixed'],
            ])
            ->add('grade', NumberType::class, [
                'label' => 'Note (/20)',
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 0.25,
                    'placeholder' => 'Note sur 20'
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'rows' => 2,
                    'placeholder' => 'Commentaires optionnels'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExamGrade::class,
            'anonymous_codes' => [],
            'is_code_fixed' => false,
        ]);
    }
}