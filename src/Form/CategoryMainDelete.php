<?php
namespace App\Form;

use App\Entity\MainCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

Class CategoryMainDelete extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('MainCategorie', EntityType::class, [
            
            'class' => MainCategory::class,
            'choice_label' => function($Category){
                return  $Category->getName();
            },
            'data_class' => null,
            'mapped'=>false,
        ]);
            
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MainCategory::class,
        ]);
    }
}