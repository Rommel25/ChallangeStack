<?php

namespace App\Form;

use App\Entity\Reponse;
use App\enum\TypeQuestionEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Question $question */

        $question = $options['question'];
//        dd($question);
//        if ($options['question']->getType() === TypeQuestionEnum::INTERVAL) {
//            $builder->add('reponse',  IntegerType::class, [
//                'label' => $question->getQuestion(),
//                'required' => true,
//                'attr' => [
//                    'min' => 0,
//                    'max' => 5,
//                ],
//                'constraints' => [
//                    new Range(min: 0, max: 5)
//                ]
//            ]);
//        }
//        dd($options['question']);
        if($options['question']->getType() === TypeQuestionEnum::CLOSE) {
            $builder->add('reponse_vf', ChoiceType::class, [
                'label' => $question->getIntitule(),
                'required' => true,
                'choices' => ['Oui' => 'Oui', 'Non' => 'Non'],
                'expanded' => true
            ]);
        } else {
            $builder->add('reponse_libre', TextType::class, [
                'label' => $question->getIntitule(),
                'required' => true,

            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
            'question' => null,
            'label' => false
        ]);
    }

}