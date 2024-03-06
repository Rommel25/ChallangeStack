<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionCreateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse_qcm', UserCreateType::class)
            ->add('classes', EntityType::class, [
                'class' => Classe::class,
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
            'data_class' => Question::class,
        ]);
    }

}