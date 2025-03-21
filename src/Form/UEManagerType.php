<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\UE;
use App\Entity\UEManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UEManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'required' => false
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'required' => false
            ])
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('codeResp', TextType::class)
            // Champ spécifique pour l'UE
            ->add('ue', EntityType::class, [
                'class' => UE::class,
                'choice_label' => function (UE $ue) {
                    return sprintf(
                        '%s - %s - %s - %s - %s',
                        $ue->getSemester()->getLevel()->getField()->getSchool()->getUniversity()->getName(),
                        $ue->getSemester()->getLevel()->getField()->getName(),
                        $ue->getSemester()->getLevel()->getName(),
                        $ue->getSemester()->getName(),
                        $ue->getName()
                    );
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin('u.uEManager', 'm')
                        ->where('m.id IS NULL')
                        ->orderBy('u.name', 'ASC');
                },
                'label' => 'UE à gérer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UEManager::class,
        ]);
    }
}
