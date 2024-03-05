<?php

namespace App\Controller;

use App\Entity\Communication;
use App\Entity\Message;
use App\Form\CommunicationType;
use App\Repository\CommunicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/communication')]
class CommunicationController extends AbstractController
{
    #[Route('/', name: 'app_communication_index', methods: ['GET'])]
    public function index(CommunicationRepository $communicationRepository): Response
    {
        return $this->render('communication/index.html.twig', [
            'communications' => $communicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_communication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $communication = new Communication();
        $form = $this->createForm(CommunicationType::class, $communication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($communication);
            $entityManager->flush();

            return $this->redirectToRoute('app_communication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('communication/new.html.twig', [
            'communication' => $communication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_communication_show', methods: ['GET'])]
    public function show(Communication $communication): Response
    {
        return $this->render('communication/show.html.twig', [
            'communication' => $communication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_communication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Communication $communication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommunicationType::class, $communication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_communication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('communication/edit.html.twig', [
            'communication' => $communication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_communication_delete', methods: ['POST'])]
    public function delete(Request $request, Communication $communication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$communication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($communication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_communication_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/sendMsg/{id}', name: 'sendMsg', methods: ['POST'])]
    public function sendMsg(Request $request, Communication $communication, EntityManagerInterface $entityManager): Response
    {
        $msg = $request->request->get('msg');
        $message = new Message();
        $message->setMessage($msg)
                ->setCommunication($communication)
                ->setExpediteur($this->getUser())
                ->setTime(new \DateTime());

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('communication/show.html.twig', [
            'communication' => $communication,
        ]);
    }
}
