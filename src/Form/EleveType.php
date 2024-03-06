<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Creneau;
use App\Entity\Eleve;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserCreateType::class)
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
            'data_class' => Eleve::class,
        ]);
    }
}
