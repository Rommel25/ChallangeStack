<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Formateur;
use App\Entity\User;
use App\enum\RoleEnum;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('password')
            ->add('plainpassword')
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'ADMIN' => 'ADMIN',
                    'USER' => 'USER',
                    'TEACHER' => 'TEACHER',
                ],
                'expanded'=>true,
                'multiple'=>true])
            ->add('token')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
