<?php

namespace App\Form;

use App\Entity\UE;
use App\Entity\Semester;
use App\Entity\Level;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

class UEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $level = $options['level'];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'UE',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom pour l\'UE'])
                ]
            ])
            ->add('code', TextType::class, [
                'label' => 'Code de l\'UE',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un code pour l\'UE'])
                ]
            ])
            ->add('credit', IntegerType::class, [
                'label' => 'Nombre de crédits',
                'attr' => ['class' => 'form-control', 'min' => 1],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nombre de crédits'])
                ]
            ])
            ->add('isCompulsory', CheckboxType::class, [
                'label' => 'UE obligatoire',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('semester', EntityType::class, [
                'class' => Semester::class,
                'choice_label' => function (Semester $semester) {
                    return sprintf(
                        '%s - %s - %s',
                        $semester->getLevel()->getField()->getName(),
                        $semester->getLevel()->getName(),
                        $semester->getName()
                    );
                },
                'query_builder' => function (EntityRepository $er) use ($level) {
                    return $er->createQueryBuilder('s')
                        ->join('s.level', 'l')
                        ->join('l.field', 'f')
                        ->where('s.level = :level')
                        ->setParameter('level', $level)
                        ->orderBy('s.code', 'ASC');
                },
                'label' => 'Semestre',
                'placeholder' => 'Sélectionnez un semestre',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un semestre'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UE::class,
        ]);

        $resolver->setRequired(['level']);
        $resolver->setAllowedTypes('level', [Level::class, 'null']);
    }
}
