<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Form\ImageType;
use App\Entity\Image;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $user = $userRepository->findOneBy(['id' => $security->getUser()->getId()]);
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'userId' => $user,
        ]);
    }

    #[Route('/profil', name: 'profil', methods: ['GET', 'POST'])]
    public function profil(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, Security $security): Response
    {
        $user = $userRepository->findOneBy(['id' => $security->getUser()->getId()]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
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

                if ($security->getUser()->getImage()) {
                    $entityManager->remove($security->getUser()->getImage());
                }

                $security->getUser()->setImage($image);
                $entityManager->flush();

                return $this->redirectToRoute('profil');
            }
        }

        //        dd($user);
        $formImg = $this->createForm(ImageType::class);

        return $this->render('user/edit.html.twig', [
            'userId' => $user,
            'form' => $form,
            'formImg' => $formImg->createView(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'userId' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        //        dd($user);
        return $this->render('user/edit.html.twig', [
            'userId' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [
            'userId' => $user,
        ], Response::HTTP_SEE_OTHER);
    }

    #[Route('/image/', name: 'app_user_image', methods: ['GET', 'POST'])]
    public function image(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
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

                if ($security->getUser()->getImage()) {
                    $entityManager->remove($security->getUser()->getImage());
                }

                $security->getUser()->setImage($image);
                $entityManager->flush();

                return $this->redirectToRoute('profil');
            }
        }

        return $this->render('image/image.html.twig', [
            'form' => $formImg->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'userId' => $user,
            'user' => $user
        ]);
    }
}
