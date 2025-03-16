<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\LevelManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;

class LevelManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom d\'utilisateur']),
                    new Length(['min' => 3, 'minMessage' => 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères'])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer une adresse email']),
                    new Email(['message' => 'Veuillez entrer une adresse email valide'])
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un prénom'])
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom'])
                ]
            ])
            ->add('codeResp', TextType::class, [
                'label' => 'Code responsable',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un code responsable'])
                ]
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('CNI', TextType::class, [
                'label' => 'CNI',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ]);
            
        // Gérer le champ de mot de passe différemment selon création ou édition
        $passwordConstraints = [
            new Length([
                'min' => 6,
                'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
            ]),
        ];
        
        if (!$isEdit) {
            // Ajouter NotBlank uniquement lors de la création
            $passwordConstraints[] = new NotBlank([
                'message' => 'Veuillez entrer un mot de passe',
            ]);
        }
        
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => !$isEdit,
            'mapped' => true,
            'first_options' => [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
                'constraints' => $passwordConstraints
            ],
            'second_options' => [
                'label' => 'Confirmer le mot de passe',
                'attr' => ['class' => 'form-control']
            ],
            'invalid_message' => 'Les deux mots de passe doivent être identiques.',
        ]);
        
        // Gérer le champ de photo de profil
        $builder->add('profilePhotoFile', FileType::class, [
            'label' => 'Photo de profil',
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new Image([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png'
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG ou PNG)'
                ])
            ]
        ]);
        
        // Requête pour les niveaux qui n'ont pas déjà un responsable ou qui sont gérés par ce responsable (en mode édition)
        $builder->add('level', EntityType::class, [
            'class' => Level::class,
            'choice_label' => function (Level $level) {
                return sprintf(
                    '%s - %s - %s - %s',
                    $level->getField()->getSchool()->getUniversity()->getName(),
                    $level->getField()->getSchool()->getName(),
                    $level->getField()->getName(),
                    $level->getName()
                );
            },
            'query_builder' => function (EntityRepository $er) use ($options, $isEdit) {
                $qb = $er->createQueryBuilder('l')
                    ->leftJoin('l.levelManager', 'm');
                
                if ($isEdit && isset($options['level_manager'])) {
                    // En mode édition, inclure à la fois les niveaux sans responsable et le niveau actuel du responsable
                    $qb->where('m.id IS NULL OR m.id = :managerId')
                        ->setParameter('managerId', $options['level_manager']->getId());
                } else {
                    // En mode création, inclure uniquement les niveaux sans responsable
                    $qb->where('m.id IS NULL');
                }
                
                return $qb->orderBy('l.name', 'ASC');
            },
            'label' => 'Niveau à gérer',
            'attr' => ['class' => 'form-control'],
            'placeholder' => 'Sélectionnez un niveau',
            'constraints' => [
                new NotBlank(['message' => 'Veuillez sélectionner un niveau'])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LevelManager::class,
            'is_edit' => false,
            'level_manager' => null,
        ]);
    }
}