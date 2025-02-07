<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\LevelManager;
use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LevelManagerType extends AbstractType
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
            ->add('function', TextType::class, [
                'label' => 'Fonction',
                'required' => false
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'required' => false
            ])
            ->add('level', EntityType::class, [
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
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->leftJoin('l.manager', 'm')
                        ->where('m.id IS NULL')
                        ->orderBy('l.name', 'ASC');
                },
                'label' => 'Niveau à gérer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LevelManager::class,
        ]);
    }
}
