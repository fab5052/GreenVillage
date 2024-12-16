<?php

namespace App\Form;

use App\Entity\Rubrics;
use App\Entity\Products;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormType;
use App\Repository\RubricsRepository;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Positive;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom'
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description'
            ])
            ->add('price', MoneyType::class, options:[
                'label' => 'Prix',
                'divisor' => 100,
                'constraints' => [
                    new Positive(
                        message: 'Le prix ne peut être négatif'
                    )
                ]
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Article disponible',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\GreaterThan([
                        'value' => 0,
                        'message' => 'Le stock doit être supérieur à 0 pour être disponible.',
                    ]),
                ],
            ])
            
            ->add('Rubrics', EntityType::class, [
                'class' => Rubrics::class,
                'choice_label' => 'label',
                'label' => 'Rubriques',
                'group_by' => 'parent.label',
                'query_builder' => function(RubricsRepository $rR){
                    return $rR->createQueryBuilder('r')
                        ->where('r.parent IS NOT NULL')
                        ->orderBy('r.label', 'ASC');
                }
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All(
                        new Image([
                            'maxWidth' => 1280,
                            'maxWidthMessage' => 'L\'image doit faire {{ max_width }} pixels de large au maximum'
                        ])
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
