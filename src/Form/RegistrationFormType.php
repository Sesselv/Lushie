<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('email')
        // ->add('agreeTerms', CheckboxType::class, [
        //     'mapped' => false,
        //     'constraints' => [
        //         new IsTrue([
        //             'message' => 'You should agree to our terms.',
        //         ]),
        //     ],
        // ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Password',
                'attr' => ['autocomplete' => 'new-password'],
            ],
            'second_options' => [
                'label' => 'Confirm Password',
                'attr' => ['autocomplete' => 'new-password'],
            ],
            'invalid_message' => 'Les mots de passe doivent être identiques.',
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).+$/',
                    'message' => 'Your password must contain at least one uppercase letter, one lowercase letter and one number or special character.',
                ]),
            ],
        ])
    ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
