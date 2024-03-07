<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Repository\EleveRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionnaireType extends AbstractType
{

    public function __construct(private ReponseRepository $reponseRepository, private Security $security, private EleveRepository $eleveRepository)
    {
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $questionnaire = $options['questionnaire'];
//        dd($questionnaire);
//        $questions = '';
//        foreach ($quetionnaire->getQuestion() as $question){
//            $questions .= $question->getQuestion();
//        }
//        dd($questions);

        foreach ($questionnaire->getQuestions() as $question){
//dd($question);
            $eleve = $this->eleveRepository->findOneBy(['user'=>$this->security->getUser()]);
//            dd($lyceen);
            $reponse = $this->reponseRepository->findOneBy(['eleve'=>$eleve, 'question' => $question]);

            if($reponse == null){
                $reponse = (new Reponse())->setQuestion($question)->setEleve($eleve);
            }
            $builder
                ->add('reponse' . $question->getId(), ReponseType::class, [
                    'data' => $reponse,
                    'question' => $question
                ]);
        }


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'questionnaire' => null,
        ]);
    }

}