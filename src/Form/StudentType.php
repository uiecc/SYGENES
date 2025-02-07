<?php
// src/Form/StudentType.php
namespace App\Form;

use App\Entity\Level;
use App\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone'
            ])
            ->add('profilePhoto', TextType::class, [
                'label' => 'Photo de profil',
                'required' => false
            ])
            ->add('cni', TextType::class, [
                'label' => 'CNI'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F'
                ],
                'label' => 'Sexe'
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance'
            ])
            ->add('placeOfBirth', TextType::class, [
                'label' => 'Lieu de naissance'
            ])
            ->add('matricule', TextType::class, [
                'label' => 'Matricule'
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => function(Level $level) {
                    return sprintf(
                        '%s - %s - %s - %s',
                        $level->getField()->getSchool()->getUniversity()->getName(),
                        $level->getField()->getSchool()->getName(),
                        $level->getField()->getName(),
                        $level->getName()
                    );
                },
                'placeholder' => 'Choisir un niveau',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->leftJoin('l.field', 'f')
                        ->leftJoin('f.school', 's')
                        ->leftJoin('s.university', 'u')
                        ->orderBy('u.name', 'ASC')
                        ->addOrderBy('s.name', 'ASC')
                        ->addOrderBy('f.name', 'ASC')
                        ->addOrderBy('l.name', 'ASC');
                },
            ])        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}