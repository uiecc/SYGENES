<?php

namespace App\Form;

use App\Entity\EC;
use App\Entity\Role;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('codeResp', TextType::class)
            // Collection d'EC
            ->add('ecs', EntityType::class, [
                'class' => EC::class,
                'choice_label' => function (EC $ec) {
                    return sprintf(
                        '%s - %s',
                        $ec->getUE()->getName(),
                        $ec->getName()
                    );
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Éléments constitutifs à enseigner'
            ])
            ->add('function')->add('function', TextType::class, [
                'label' => 'Fonction',
                'required' => false
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'required' => false
            ])

            ->add('department')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
