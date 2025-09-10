<?php

namespace App\Form;

use App\Entity\Soap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
class SoapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         ->add('name', null, [
        'constraints' => [new NotBlank(['message' => 'Le nom est obligatoire.'])]
    ])
    ->add('description', null, [
        'constraints' => [new NotBlank(['message' => 'La description est obligatoire.'])]
    ])
    ->add('benefits', null, [
        'constraints' => [new NotBlank(['message' => 'Les bienfaits sont obligatoires.'])]
    ])
    ->add('precautions', null, [
        'constraints' => [new NotBlank(['message' => 'Les précautions sont obligatoires.'])]
    ])
    ->add('utilization', null, [
        'constraints' => [new NotBlank(['message' => 'L’utilisation est obligatoire.'])]
    ])
    ->add('effect', null, [
        'constraints' => [new NotBlank(['message' => 'L’effet est obligatoire.'])]
    ])
    ->add('skinType', null, [
        'constraints' => [new NotBlank(['message' => 'Le type de peau est obligatoire.'])]
    ])
    ->add('action', null, [
        'constraints' => [new NotBlank(['message' => 'L’action est obligatoire.'])]
    ])
    ->add('usageTime', null, [
        'constraints' => [new NotBlank(['message' => 'Le temps d’usage est obligatoire.'])]
    ])
->add('images', FileType::class, [
    'mapped' => false,
    'multiple' => true,
    'required' => false, 
    'constraints' => [
        new All([
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg','image/png'],
                    'mimeTypesMessage' => 'Merci de télécharger uniquement des images JPG ou PNG.'
                ])
            ]
        ])
    ]
]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Soap::class,
        ]);
    }
}
