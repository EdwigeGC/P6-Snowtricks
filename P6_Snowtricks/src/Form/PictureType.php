<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PictureType extends AbstractType
{
  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' =>[
                    'placeholder'=>"Choose a title for your picture"
                ]
            ])
            ->add('file', FileType::class, [
                'multiple' => false,
                'required' =>false,
                /*'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' =>[
                            'application/pdf',
                            'application/png',
                            'application/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF, PNG or JPEG document',
                    ])
                ],*/
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
