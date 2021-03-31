<?php
namespace App\Form;

use App\Entity\PromoCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

Class PromoCodeType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('Code', null , ['label'=> false, 'attr' => ['placeholder' => 'Code Promo','class' => 'input' ]])
            ->add('reduction', IntegerType::class, ['label'=> false, 'attr' => ['placeholder' => 'Pourcentage de reduction']]);
           
            
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PromoCode::class,
        ]);
    }
}