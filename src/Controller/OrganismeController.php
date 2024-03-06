<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\Organisme;
use App\Form\OrganismeType;
use App\Repository\OrganismeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Form\ImageType;
use App\Entity\Image;
use App\Repository\FormateurRepository;
use LogicException;

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
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, FormateurRepository $formateurRepository): Response
    {
        $organisme = new Organisme();
        $form = $this->createForm(OrganismeType::class, $organisme);
        $form->handleRequest($request);

        $user = $security->getUser();
        $formateur = $formateurRepository->findOneBy(['user' => $user->getId()]);

        $organisme->setFormateur($formateur);

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
    public function edit(Request $request, Organisme $organisme, EntityManagerInterface $entityManager, Security $security): Response
    {
        $form = $this->createForm(OrganismeType::class, $organisme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organisme_index', [], Response::HTTP_SEE_OTHER);
        }

        $formImg = $this->createForm(ImageType::class);
        $formImg->handleRequest($request);

        if ($formImg->isSubmitted() && $formImg->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $formImg['imageFile']->getData();

            if ($imageFile) {
                $imageContent = file_get_contents($imageFile->getRealPath());
                $base64Content = base64_encode($imageContent);

                $image = new Image();

                $image->setData($base64Content);
                $image->setContentType($imageFile->getMimeType());
                $image->setName($imageFile->getClientOriginalName());

                $entityManager->persist($image);
                $entityManager->flush();

                if ($organisme->getImage()) {
                    $oldImage = $organisme->getImage();
                    // Mettre à null la référence de l'image dans organisme
                    $organisme->setImage(null);
                    $entityManager->persist($organisme);
                    $entityManager->flush();

                    // Ensuite, supprimez l'image
                    $entityManager->remove($oldImage);
                    $entityManager->flush();
                }

                $organisme->setImage($image);
                $entityManager->flush();

                return $this->redirectToRoute('app_atelier');
            }
        }

        return $this->render('organisme/edit.html.twig', [
            'organisme' => $organisme,
            'form' => $form,
            'formImg' => $formImg->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_organisme_delete', methods: ['POST'])]
    public function delete(Request $request, Organisme $organisme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $organisme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($organisme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organisme_index', [], Response::HTTP_SEE_OTHER);
    }
}
