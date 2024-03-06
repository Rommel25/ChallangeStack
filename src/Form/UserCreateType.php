<?php

namespace App\Form;

use App\Entity\User;
use PhpCsFixer\Tokenizer\Analyzer\Analysis\SwitchAnalysis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom', options: ['required'=>false])
            ->add('prenom', options: ['required'=>false])
            ->add('password', PasswordType::class, ["required"=>false])
            ->add('plainpassword', PasswordType::class, ["required"=>false])
            ->add('submit', SubmitType::class)
            // Add other User fields
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}