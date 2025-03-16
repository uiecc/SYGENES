<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\Semester;
use App\Entity\LevelManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class SemesterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $levelManager = $options['levelManager'];
        
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du semestre',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom pour le semestre']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('code', TextType::class, [
                'label' => 'Code du semestre',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un code pour le semestre']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le code ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => function (Level $level) {
                    return sprintf('%s - %s', 
                        $level->getField()->getName(), 
                        $level->getName()
                    );
                },
                'query_builder' => function (EntityRepository $er) use ($levelManager) {
                    return $er->createQueryBuilder('l')
                        ->where('l.id = :levelId')
                        ->setParameter('levelId', $levelManager->getLevel()->getId())
                        ->orderBy('l.name', 'ASC');
                },
                'label' => 'Niveau',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez un niveau',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un niveau'])
                ],
                'disabled' => true // Le rendre désactivé puisqu'il n'y a qu'une seule option
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Semester::class,
        ]);
        
        $resolver->setRequired(['levelManager']);
        $resolver->setAllowedTypes('levelManager', [LevelManager::class]);
    }
}