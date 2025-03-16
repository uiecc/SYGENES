<?php

namespace App\Form;

use App\Entity\UE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcademicRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $student = $options['student'];
        $academicYear = $options['academic_year'];
        $registeredUEs = $options['registered_ues'];
        $availableUEs = $options['available_ues'];
        
        // Grouper les UE par semestre pour un affichage plus clair
        $uesBySemester = [];
        foreach ($availableUEs as $ue) {
            $semesterCode = $ue->getSemester()->getCode();
            if (!isset($uesBySemester[$semesterCode])) {
                $uesBySemester[$semesterCode] = [];
            }
            $uesBySemester[$semesterCode][] = $ue;
        }

        // Pour chaque semestre, ajouter un groupe d'UE au formulaire
        foreach ($uesBySemester as $semesterCode => $ues) {
            // Utiliser un identifiant valide pour le nom du champ (sans espaces ni caractères spéciaux)
            $fieldName = 'semester_' . preg_replace('/[^a-z0-9_]/', '_', strtolower($semesterCode));
            
            // Obtenir le nom du semestre pour l'affichage
            $semesterName = !empty($ues) ? $ues[0]->getSemester()->getName() : $semesterCode;
            
            $builder->add($fieldName, EntityType::class, [
                'class' => UE::class,
                'choices' => $ues,
                'choice_label' => function (UE $ue) {
                    return sprintf('%s (%s) - %d crédits %s', 
                        $ue->getName(), 
                        $ue->getCode(), 
                        $ue->getCredit(),
                        $ue->isCompulsory() ? '- Obligatoire' : '- Optionnel'
                    );
                },
                'choice_attr' => function(UE $ue) use ($student, $academicYear) {
                    $attributes = [
                        'data-credits' => $ue->getCredit(),
                        'data-compulsory' => $ue->isCompulsory() ? '1' : '0',
                        'class' => $ue->isCompulsory() ? 'compulsory-ue' : 'optional-ue'
                    ];
                    
                    // Désactiver les UE obligatoires déjà inscrites
                    if ($ue->isCompulsory() && $student->isRegisteredToUE($ue, $academicYear)) {
                        $attributes['disabled'] = 'disabled';
                    }
                    
                    return $attributes;
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Semestre ' . $semesterName . ' (' . $semesterCode . ')',
                'required' => false,
                'data' => array_filter($registeredUEs, function(UE $ue) use ($semesterCode) {
                    return $ue->getSemester()->getCode() === $semesterCode;
                }),
                'attr' => [
                    'class' => 'ue-selection-group',
                    'data-semester' => $semesterCode
                ],
                'row_attr' => [
                    'class' => 'semester-section'
                ],
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => 'Enregistrer mon inscription académique',
            'attr' => ['class' => 'btn btn-primary mt-3'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'student',
            'academic_year',
            'registered_ues',
            'available_ues',
        ]);
        
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}