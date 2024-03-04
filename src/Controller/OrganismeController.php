<?php

namespace App\Controller;

use App\Entity\Organisme;
use App\Form\OrganismeType;
use App\Repository\OrganismeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/organisme')]
class OrganismeController extends AbstractController
{
    #[Route('/', name: 'app_organisme_index', methods: ['GET'])]
    public function index(OrganismeRepository $organismeRepository): Response
    {
        return $this->render('organisme/index.html.twig', [
            'organismes' => $organismeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_organisme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organisme = new Organisme();
        $form = $this->createForm(OrganismeType::class, $organisme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organisme);
            $entityManager->flush();

            return $this->redirectToRoute('app_organisme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organisme/new.html.twig', [
            'organisme' => $organisme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organisme_show', methods: ['GET'])]
    public function show(Organisme $organisme): Response
    {
        return $this->render('organisme/show.html.twig', [
            'organisme' => $organisme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_organisme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organisme $organisme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganismeType::class, $organisme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organisme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organisme/edit.html.twig', [
            'organisme' => $organisme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organisme_delete', methods: ['POST'])]
    public function delete(Request $request, Organisme $organisme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organisme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($organisme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organisme_index', [], Response::HTTP_SEE_OTHER);
    }
}
