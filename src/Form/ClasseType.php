<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('section')
            ->add('eleves', EntityType::class, [
                'class' => Eleve::class,
                'multiple' => true,
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'multiple' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
