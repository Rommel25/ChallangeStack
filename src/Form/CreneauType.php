<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Cours;
use App\Entity\Creneau;
use App\Entity\Eleve;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreneauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('debut')
            ->add('fin')
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
'choice_label' => 'id',
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
'choice_label' => 'id',
            ])
            ->add('eleves', EntityType::class, [
                'class' => Eleve::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Creneau::class,
        ]);
    }
}
