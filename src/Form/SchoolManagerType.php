<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\School;
use App\Entity\SchoolManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champs de base hérités de User et Responsable
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur'
            ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('codeResp', TextType::class, [
                'label' => 'Code Responsable'
            ])
            ->add('function', TextType::class, [
                'label' => 'Fonction',
                'required' => false
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'required' => false
            ])
            // Champ spécifique au SchoolManager
            ->add('school', EntityType::class, [
                'class' => School::class,
                'choice_label' => function(School $school) {
                    return sprintf('%s - %s', 
                        $school->getUniversity()->getName(),
                        $school->getName()
                    );
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->leftJoin('s.manager', 'm')
                        ->where('m.id IS NULL')
                        ->orderBy('s.name', 'ASC');
                },
                'label' => 'École à gérer'
            ]);
    }
}