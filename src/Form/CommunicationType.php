<?php

namespace App\Form;

use App\Entity\Communication;
use App\Entity\Eleve;
use App\Entity\Formateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
'choice_label' => 'id',
            ])
            ->add('eleve', EntityType::class, [
                'class' => Eleve::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Communication::class,
        ]);
    }
}
