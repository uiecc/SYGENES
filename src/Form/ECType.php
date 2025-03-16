<?php

namespace App\Form;

use App\Entity\EC;
use App\Entity\Teacher;
use App\Entity\UE;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ECType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code de l\'EC',
                'attr' => [
                    'placeholder' => 'Ex: INFO123',
                    'class' => 'form-control'
                ],
                'help' => 'Le code unique identifiant cet élément constitutif'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'EC',
                'attr' => [
                    'placeholder' => 'Ex: Programmation Orientée Objet',
                    'class' => 'form-control'
                ],
                'help' => 'Le nom complet de l\'élément constitutif'
            ])
            ->add('credit', IntegerType::class, [
                'label' => 'Crédits',
                'attr' => [
                    'min' => 1,
                    'max' => 20,
                    'class' => 'form-control'
                ],
                'help' => 'Nombre de crédits attribués à cet EC'
            ])
            ->add('hasTP', CheckboxType::class, [
                'label' => 'Cet EC comporte des travaux pratiques (TP)',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'help' => 'Cochez cette case si cet EC comporte des travaux pratiques notés'
            ])
            ->add('ue', EntityType::class, [
                'class' => UE::class,
                'choice_label' => function (UE $ue) {
                    return sprintf('%s - %s (Semestre %s - %s)', 
                        $ue->getCode(), 
                        $ue->getName(), 
                        $ue->getSemester()->getCode(),
                        $ue->getSemester()->getLevel()->getName()
                    );
                },
                'label' => 'Unité d\'Enseignement',
                'placeholder' => '-- Sélectionnez une UE --',
                'attr' => [
                    'class' => 'form-select'
                ],
                'help' => 'L\'unité d\'enseignement à laquelle cet EC appartient',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.semester', 'ASC')
                        ->addOrderBy('u.code', 'ASC');
                },
                'group_by' => function (UE $ue) {
                    $semester = $ue->getSemester();
                    $level = $semester->getLevel();
                    return sprintf('%s - %s', $level->getName(), $semester->getName());
                }
            ])
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => function (Teacher $teacher) {
                    return sprintf('%s %s', $teacher->getFirstName(), $teacher->getLastName());
                },
                'label' => 'Enseignant responsable',
                'placeholder' => '-- Sélectionnez un enseignant --',
                'required' => false,
                'attr' => [
                    'class' => 'form-select'
                ],
                'help' => 'L\'enseignant responsable de cet EC (optionnel)',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.lastName', 'ASC')
                        ->addOrderBy('t.firstName', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EC::class,
        ]);
    }
}