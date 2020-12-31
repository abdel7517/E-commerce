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
            ->add('name', null , ['label'=> false, 'attr' => ['placeholder' => 'Votre nom','class' => 'input' ]])
            ->add('numberAdress', IntegerType::class, ['label'=> false, 'attr' => ['placeholder' => '4', 'class' => 'input']])
            ->add('nameAdress', null, ['label'=> false, 'attr' => ['placeholder' => 'Rue Marguerite Long','class' => 'input']])
            ->add('postalCode', IntegerType::class, ['label'=> false, 'attr' => ['placeholder' => '75017', 'class' => 'input']])
            ->add('country', null, ['label'=> false, 'attr' => ['placeholder' => 'Pays', 'class' => 'input', 'class' => 'input']])
            ->add('expedition', ChoiceType::class, [
                'row_attr' => ['class' => 'choice input'],
                'required'=>true,
                'mapped'=>false,
                'label'=> false, 
                'choices'  => [
                    'Récupération en boutique Rue Marcel Dassault 93140 Bondy' => "récupération",
                    // 'Livraison en Île-de-France : 90 € ' => 'livraison',
                ],
            ]);
            
           
            
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}