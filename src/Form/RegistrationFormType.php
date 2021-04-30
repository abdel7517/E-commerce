<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
// ...



class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, ['label'=> false, 'attr' => ['placeholder' => 'Votre mail','class' => 'input',
            ]])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=> false, 'attr' => ['placeholder' => 'Votre nom' ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez acceptez les C.G.U.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identique.',
                'label'=> false, 'attr' => ['class' => 'input' ],
                'options' => ['attr' => ['class' => 'password-field input']],
                'required' => true,
                'first_options'  => ['label' => false],
                'second_options' => ['label' => false],

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => array(
                'class' => 'flex-column-center'
            )
        ]);
    }
}
