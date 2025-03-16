<?php

namespace App\Form;

use App\Entity\Field;
use App\Entity\Level;
use App\Entity\Semester;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PVSelectionType extends AbstractType
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('field', EntityType::class, [
                'class' => Field::class,
                'choices' => $options['fields'],
                'choice_label' => function (Field $field) {
                    return $field->getCode() . ' - ' . $field->getName();
                },
                'placeholder' => 'Sélectionnez une filière',
                'label' => 'Filière',
                'required' => true,
                'attr' => [
                    'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', 
                    'data-level-url' => $this->urlGenerator->generate('app_api_levels_by_field')
                ]
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'placeholder' => 'Sélectionnez un niveau',
                'label' => 'Niveau',
                'required' => true,
                'attr' => [
                    'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', 
                    'data-semester-url' => $this->urlGenerator->generate('app_api_semesters_by_level')
                ],
                'choices' => [],
                'disabled' => true,
            ])
            ->add('semester', EntityType::class, [
                'class' => Semester::class,
                'placeholder' => 'Sélectionnez un semestre',
                'label' => 'Semestre',
                'required' => true,
                'attr' => [
                    'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'
                ],
                'choices' => [],
                'disabled' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Générer le PV',
                'attr' => [
                    'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fields' => [],
        ]);
    }
}