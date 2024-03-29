<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Note;
use App\enum\TypeQuestionEnum;
use App\Form\EvaluationType;
use App\Form\QuestionnaireType;
use App\Repository\EleveRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/evaluation')]
class EvaluationController extends AbstractController
{
    #[Route('/', name: 'app_evaluation_index', methods: ['GET'])]
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_evaluation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData()->getQuestions() as $question) {
                $evaluation->addQuestion($question);
                $question->setEvaluation($evaluation);
            }
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('app_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evaluation_show', methods: ['GET'])]
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evaluation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evaluation_delete', methods: ['POST'])]
    public function delete(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evaluation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/questionnaire/{id}', name: 'app_questionnaire', methods: ['GET', 'POST'])]
    public function questionnaire($id, Request $request, AuthenticationUtils $authenticationUtils, Security $security, EvaluationRepository $evaluationRepository, SessionInterface $session, EleveRepository $eleveRepository, EntityManagerInterface $entityManager): Response
    {
        $questionnaire = $evaluationRepository->findOneBy(['cours' => $id]);
        $form = $this->createForm(QuestionnaireType::class, null, [
            'questionnaire' => $questionnaire
        ]);
        $eleve = $eleveRepository->findOneBy(['user' => $this->getUser()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = new Note();
            $pts = 0;
            $nbr = 0;
            foreach ($form->getData() as $reponse) {
                $entityManager->persist($reponse);
                if ($reponse->getQuestion()->getType() === TypeQuestionEnum::CLOSE) {
                    $nbr++;
                    if ($reponse->getQuestion()->isReponseVf() === $reponse->isReponseVf()) {
                        $pts++;
                    }
                }
            };
            $note->setEleve($eleve);
            $note->setEvaluation($questionnaire);
            $note->setNote($pts);
            $eleve->addNote($note);
            $entityManager->persist($note);
            $entityManager->persist($eleve);
            $entityManager->flush();
            $session->getFlashBag()->add('success', 'Vos réponses ont bien été enregistrés. Note : ' . $pts . '/' . $nbr . ' sur les questions v/f');
            return $this->redirectToRoute('profil');
        }
        return $this->render('evaluation/reponse.html.twig', [
            'form' => $form,
        ]);
    }
}
