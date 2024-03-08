<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Form\EleveType;
use App\Repository\EleveRepository;
use App\Repository\ClasseRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Uuid;

#[Route('/eleve')]
class EleveController extends AbstractController
{
    #[Route('/', name: 'app_eleve_index', methods: ['GET'])]
    public function index(EleveRepository $eleveRepository): Response
    {
        return $this->render('eleve/index.html.twig', [
            'eleves' => $eleveRepository->findAll(),
        ]);
    }

    #[Route('/stats', name: 'app_eleve_stats', methods: ['GET','POST'])]
    public function stats(Request $request, EntityManagerInterface $entityManager,NoteRepository $noteRepository, EleveRepository $eleveRepository, Security $security): Response
    {
//        dd('ici');
        $note = [];
        $eleve = $eleveRepository->findOneBy(['id'=>$security->getUser()->getId()]);
//        dd($eleve->getId());
        $notes = $noteRepository->findBy(['eleve' => $eleve->getId()]);
//        dd($eleve->getNotes(), $notes);
        return $this->render('eleve/stats.html.twig', [
            'notes' => $notes
        ]);
    }

    #[Route('/new/{idClasse?}', name: 'app_eleve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, $idClasse = null, ClasseRepository $classeRepository): Response
    {
        $eleve = new Eleve();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($idClasse) {
            $classe = $classeRepository->find($idClasse);
            $eleve->addClass($classe);
        }

//                dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
//            dd($form->getData());
            $eleve->getUser()->setPassword('');
            $eleve->getUser()->setPlainPassword('');
            $uniqueId = uniqid();
            $eleve->getUser()->setToken($uniqueId);
            $entityManager->persist($eleve);
            $entityManager->flush();

            $email = (new Email())
                ->from('support@academiaflow.com')
                ->to($eleve->getUser()->getEmail())
                ->subject('Inscription')
                ->text('Votre invitation pour vous inscrire à la plateforme AcademiaFlow')
                ->html('<p>Set password here http://challenge.local/premiereconnexion/' . $uniqueId . '</p>');

            $mailer->send($email);

            return $this->redirectToRoute('app_eleve_index');
        }

        return $this->render('eleve/new.html.twig', [
            'eleve' => $eleve,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_eleve_show', methods: ['GET'])]
    public function show(Eleve $eleve): Response
    {
        return $this->render('eleve/show.html.twig', [
            'eleve' => $eleve,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_eleve_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Eleve $eleve, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);
        //        dd($eleve->getClasses());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eleve);
            $entityManager->flush();

            return $this->redirectToRoute('app_eleve_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eleve/edit.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_eleve_delete', methods: ['POST'])]
    public function delete(Request $request, Eleve $eleve, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $eleve->getId(), $request->request->get('_token'))) {
            $entityManager->remove($eleve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_eleve_index', [], Response::HTTP_SEE_OTHER);
    }


}
