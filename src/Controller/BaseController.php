<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormateurRepository;
use Symfony\Bundle\SecurityBundle\Security;


class BaseController extends AbstractController
{

    #[Route('/', name: 'app_atelier')]
    public function index(FormateurRepository $formateurRepository, Security $security): Response
    {
        // Fetch all ateliers from the repository

        $formateur = $formateurRepository->findOneBy(['id'=>$security->getUser()->getId()]);
        $userId = $security->getUser();

        return $this->render('base.html.twig', [
            "formateur" => $formateur,
            "userId" => $userId
        ]);
    }

     #[Route('/formation/${idOrganisme}', name: 'app_formation_by_organisme')]
        public function list(FormateurRepository $formateurRepository, Security $security): Response
        {
            // Fetch all ateliers from the repository

            return $this->render('formationByOrganisme.html.twig', [
            ]);
        }

}