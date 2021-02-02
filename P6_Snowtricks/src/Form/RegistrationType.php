<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    /**
     * Undocumented function
     *
     * @param string $label
     * @return array
     */
    public function setConfigurationAttribute($label){
        return[
            'label'=>$label,
            ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, $this->setConfigurationAttribute("Choose a username"))
            ->add('email',EmailType::class, $this->setConfigurationAttribute("Write your Email address"))
            ->add('password',PasswordType::class, $this->setConfigurationAttribute("Choose a password"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}