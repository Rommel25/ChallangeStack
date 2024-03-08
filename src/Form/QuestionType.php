<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\Proposition;
use App\Entity\Question;
use App\enum\TypeQuestionEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule')
            ->add('type', ChoiceType::class, [
                'choices' => TypeQuestionEnum::getChoices()
            ])
//            ->add('reponse_qcm', EntityType::class ,[
//                'label'=> 'Réponse attendu pour un qcm',
//                'class' => Proposition::class
//                ])
//            ->add('propositions', CollectionType::class, [
//                'entry_type' => PropositionType::class, // Créez un sous-formulaire pour la réponse QCM
//                'allow_add' => true, // Permet à l'utilisateur d'ajouter de nouveaux éléments à la collection
//                'allow_delete' => true, // Permet à l'utilisateur de supprimer des éléments de la collection
//                'by_reference' => false, // Assurez-vous que les objets de la collection sont bien gérés par référence
//                'label' => 'Proposition pour QCM',
//            ])
            ->add('reponse_vf', ChoiceType::class, [
                'choices' => [
                    'Vrai' => true,
                    'Faux' => false,
                ],
                'label' => 'Réponse Vrai ou Faux attendu'])
            ->add('evaluation', EntityType::class, [
                'class' => Evaluation::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
