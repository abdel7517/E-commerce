<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\MainCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

Class CategoryType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('categoriesPrincipale', EntityType::class, [
                'class' => MainCategory::class,
                'choice_label' => 'name',
                'data_class' => null,
                'mapped'=>false
            ])
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, ['data_class' => null,  'mapped'=>false]);
            
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}