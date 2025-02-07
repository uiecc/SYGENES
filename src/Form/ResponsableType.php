<?php

namespace App\Form;

use App\Entity\Responsable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class ResponsableType extends AbstractType
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
           ->add('codeResp', TextType::class, [
               'label' => 'Code Responsable'
           ])
       ;
   }

   public function configureOptions(OptionsResolver $resolver): void
   {
       $resolver->setDefaults([
           'data_class' => Responsable::class,
       ]);
   }
}