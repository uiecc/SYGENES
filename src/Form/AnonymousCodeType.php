<?php

namespace App\Form;

use App\Entity\AnonymousCode;
use App\Entity\Exam;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnonymousCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code anonyme',
                'required' => true,
                'attr' => [
                    'maxlength' => 10,
                    'placeholder' => 'Code anonyme'
                ],
                'help' => 'Un code unique pour anonymiser l\'étudiant'
            ])
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choices' => $options['students'] ?? [],
                'choice_label' => function (Student $student) {
                    return $student->getLastName() . ' ' . $student->getFirstName() . ' (' . $student->getMatricule() . ')';
                },
                'placeholder' => 'Choisir un étudiant',
                'label' => 'Étudiant',
                'required' => true,
            ])
            ->add('exam', EntityType::class, [
                'class' => Exam::class,
                'choice_label' => function (Exam $exam) {
                    return $exam->getEc()->getCode() . ' - ' . $exam->getExamDate()->format('d/m/Y');
                },
                'placeholder' => 'Choisir un examen',
                'label' => 'Examen',
                'required' => true,
                'disabled' => $options['is_exam_fixed'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnonymousCode::class,
            'students' => [],
            'is_exam_fixed' => false,
        ]);
    }
}