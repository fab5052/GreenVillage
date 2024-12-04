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
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
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
        ->add('plainPassword', PasswordType::class, [
            'label' => 'Mot de passe',
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrez votre mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/',
                    'message' => 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial',
                ]),
            ],
        ])
        ->add('confirmPassword', PasswordType::class, [
            'label' => 'Confirmer le nouveau mot de passe',
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez confirmer votre nouveau mot de passe.',
                ]),
            ],
        ])
   
             ;
             $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                 $form = $event->getForm();
                 $plainPassword = $form->get('plainPassword')->getData();
                 $confirmPassword = $form->get('confirmPassword')->getData();
     
                 if ($plainPassword !== $confirmPassword) {
                     $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
                 }
             });
         }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}