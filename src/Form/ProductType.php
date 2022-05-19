<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class,[
                'attr'=>[
                    'placeholder'=>'libelé'
                ]
            ])
            ->add('description', TextareaType::class,[
                'attr'=>[
                    'placeholder'=>'description'
                ]
            ])
            ->add('price',IntegerType::class,[
                'attr'=>[
                    'placeholder'=>'Prix'
                ]
            ])
            ->add('stock',IntegerType::class,[
                'attr'=>[
                    'placeholder'=>'Stock'
                ]
            ])
            ->add('isActif',ChoiceType::class,[
                'choices'=>[
                    'Is Actif'=>true,
                    'is not Actif' =>false
                ],
                'attr'=>[
                    'placeholder'=>'Est actif'
                ]

            ])
            ->add('brand',EntityType::class,[
                'class' => Brand::class,
                    //pour générer des boutons radio
                    'choice_label'=>'label',
                    'expanded'=>true,
                    'label'=>'Marque'
                ]
            )
            //Ajouter checkbox
            ->add('categories', EntityType::class,[
                'class' => Category::class,
                'query_builder'=>function(CategoryRepository $cr){
                return $cr->createQueryBuilder('c')
                    ->where('c.id > 2');
//                    ->orderBy('c.categoryParent','ASC');
                },
                //Pour générer une checkbox
                'choice_label'=>'label',
                'expanded'=>true,
                'multiple'=>true,
                'label'=>'Categorie'
            ])

            ->add('image',FileType::class,[
                'mapped'=>false,
                'label'=>'Ajouter un fichier'
            ])

            ->add('Submit', SubmitType::class,[
                'label'=>'Envoyer',
                'attr'=>[
                    'class'=>'btn btn-green'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
