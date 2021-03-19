<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\AllCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProductEditType extends AbstractType
{
 

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('description')
            ->add('price', NumberType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'data_class' => null,
                'mapped'=>false
            ])
            ->add('reference')
            ->add('PriceML',   CheckboxType::class, [
                'label'    => 'Prix au mètre linéaire',
                'required' => false,
            ])
            ->add('PriceOfML',  TextType::class,  [
                'label'    => 'Prix du mètre linéaire',
                'required' => false
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
