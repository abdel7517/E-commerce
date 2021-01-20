<?php
namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

Class OrderType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null , ['label'=> false, 'attr' => ['placeholder' => 'Nom et Prénom','class' => 'input' ]])
            ->add('numberAdress', IntegerType::class, ['label'=> false, 'attr' => ['placeholder' => 'Numéro de voie', 'class' => 'input']])
            ->add('nameAdress', null, ['label'=> false, 'attr' => ['placeholder' => 'Nom de la voie','class' => 'input']])
            ->add('postalCode', IntegerType::class, ['label'=> false, 'attr' => ['placeholder' => 'Code Postal', 'class' => 'input']])
            ->add('country', null, ['label'=> false, 'attr' => ['placeholder' => 'Pays', 'class' => 'input', 'class' => 'input']])
            // ->add('expedition', ChoiceType::class, [
            //     'row_attr' => ['class' => 'choice input'],
            //     'required'=>true,
            //     'mapped'=>false,
            //     'label'=> false, 
            //     'choices'  => [
            //         'Vos articles seront disponibles dans notre boutique' => "récupération",
            //         // 'Livraison en Île-de-France : 90 € ' => 'livraison',
            //     ],
            // ]);
            ;
           
            
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}