<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\AllCategory;
use App\Entity\MainCategory;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Product3Type extends AbstractType
{
 

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('image', FileType::class,
            ['data_class' => null,'multiple' => false, 'mapped'=>false, 'label' => 'images principale', ])
            ->add('images', FileType::class, 
            ['data_class' => null, 'multiple'=>true, 'mapped'=>false, 'label' => 'autres image','required'=> false ])
            ->add('categorie', EntityType::class, [
            
                'class' => Category::class,
                'choice_label' => function($Category){
                    return  $Category->getName().  ' - ' .$Category->getMainCategory();
                },
                'data_class' => null,
                'mapped'=>false,
            ])
            ->add('reference')
            ->add('PriceML',   CheckboxType::class, [
                'label'    => 'Prix au mètre linéaire',
                'required' => false,
            ])
            ->add('PriceOfML', null,   [
                'label'    => 'Prix du mètre linéaire',
            ])
            ->add('avaibility', NumberType::class, [
                'label'    => 'Délais de fabrication ( en semaine )',
            ] );
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

