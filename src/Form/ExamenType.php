<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Cours;
use App\Entity\Eleve;
use App\Entity\Evaluation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', QuestionCreateType::class)
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false
            ])
//            ->add('creneaux', EntityType::class, [
//                'class' => Creneau::class,
//'choice_label' => 'id',
//'multiple' => true,
//                'required' => false
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }

}