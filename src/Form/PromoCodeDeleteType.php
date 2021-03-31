<?php
namespace App\Form;

use App\Entity\PromoCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

Class PromoCodeDeleteType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('promoCode', EntityType::class, [
            'class' => PromoCode::class,
            'choice_label' => function($promoCode){
                return  $promoCode->getCode();
            },
            'data_class' => null,
            'mapped'=>false,
        ]);
            
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PromoCode::class,
        ]);
    }
}