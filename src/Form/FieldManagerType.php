<?php

namespace App\Form;

use App\Entity\Field;
use App\Entity\FieldManager;
use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FieldManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champs de base communs à tous les responsables
        $builder
            ->add('function', TextType::class, [
                'label' => 'Fonction',
                'required' => false
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'required' => false
            ])

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
            // Champ spécifique pour la filière
            ->add('field', EntityType::class, [
                'class' => Field::class,
                'choice_label' => function (Field $field) {
                    return sprintf(
                        '%s - %s - %s',
                        $field->getSchool()->getUniversity()->getName(),
                        $field->getSchool()->getName(),
                        $field->getName()
                    );
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->leftJoin('f.manager', 'm')
                        ->where('m.id IS NULL')
                        ->orderBy('f.name', 'ASC');
                },
                'label' => 'Filière à gérer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FieldManager::class,
        ]);
    }

}
