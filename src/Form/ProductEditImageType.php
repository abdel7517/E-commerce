<?php

namespace App\Form;

use App\Entity\AllCategory;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductEditImageType extends AbstractType
{
 

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('image', FileType::class,
            ['data_class' => null,'multiple' => false, 'mapped'=>false, 'label' => 'images principale'])
            ->add('images', FileType::class, 
            ['data_class' => null, 'multiple'=>true, 'mapped'=>false, 'label' => 'autres image']);
            
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
