<?php

namespace App\Form;

use App\Entity\Soap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
class SoapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('benefits')
            ->add('precautions')
            ->add('utilization')
            ->add('effect')
            ->add('skinType')
            ->add('action')
            ->add('usageTime')
            ->add('images', FileType::class, [
        'label' => 'Images (max 3)',
        'mapped' => false,
        'multiple' => true,
        'required' => false,
        'constraints' => [
            new File([
                'maxSize' => '2M',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                ],
                'mimeTypesMessage' => 'Merci de télécharger uniquement des images JPG ou PNG.',
            ])
        ],
    ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Soap::class,
        ]);
    }
}
