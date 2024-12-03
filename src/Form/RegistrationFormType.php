<?php

namespace App\Form;

use App\Entity\Users;
use Enum\UserRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'E-mail'
        ])
        ->add('lastname', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Nom'
        ])
        ->add('firstname', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Prénom'
        ])
        ->add('address', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Adresse'
        ])
        ->add('zipcode', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Code postal'
        ])
        ->add('city', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Ville'
        ])
        ->add('RGPDConsent', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
            'label' => 'En m\'inscrivant à ce site j\'accepte...'
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
            ],
            'second_options' => [
                'label' => 'Confirmer le mot de passe',
                'attr' => ['class' => 'form-control'],
            ],
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    'max' => 4096,
                ]),
            ],
             'label' => 'Mot de passe'
   
        ]);

                           
}        
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
