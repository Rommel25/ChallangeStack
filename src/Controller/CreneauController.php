<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Creneau;
use App\Entity\Formateur;
use App\Form\CreneauType;
use App\Repository\CreneauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/creneau')]
class CreneauController extends AbstractController
{
    #[Route('/', name: 'app_creneau_index', methods: ['GET'])]
    public function index(CreneauRepository $creneauRepository): Response
    {
        return $this->render('creneau/index.html.twig', [
            'creneaus' => $creneauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_creneau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $creneau = new Creneau();
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($creneau);
            $entityManager->flush();

            return $this->redirectToRoute('app_creneau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneau/new.html.twig', [
            'creneau' => $creneau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_creneau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Creneau $creneau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creneau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneau/edit.html.twig', [
            'creneau' => $creneau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creneau_delete', methods: ['POST'])]
    public function delete(Request $request, Creneau $creneau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$creneau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($creneau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creneau_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/calendar', name: 'app_creneau_calendar', methods: ['GET'])]
    public function calendar(Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse
    {
        $user = $security->getUser();
        $formateur = $entityManager->getRepository(Formateur::class)->findOneBy(['user' => $user]);

        $cours = $entityManager->getRepository(Cours::class)->findBy(['formateur' => $formateur]);
        $response = [];
        foreach ($cours as $cour) {
            $creneaux = $entityManager->getRepository(Creneau::class)->findBy(['cours' => $cour]);
            foreach ($creneaux as $creneau) {
                $response[] = [
                    'title' => $creneau->getCours()->getTitre(),
                    'url'   => '/creneau/' . $creneau->getId(),
                    'start' => $creneau->getDebut()->format('Y-m-d H:i:s'),
                    'end'   => $creneau->getFin()->format('Y-m-d H:i:s'),
                ];
            }
        }
        return new JsonResponse($response);
    }

    #[Route('/{id}', name: 'app_creneau_show', methods: ['GET'])]
    public function show(Creneau $creneau): Response
    {
        return $this->render('creneau/show.html.twig', [
            'creneau' => $creneau,
        ]);
    }
}
