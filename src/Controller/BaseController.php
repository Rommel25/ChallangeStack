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
        if(!$security->getUser()){
            return $this->redirectToRoute('app_login');
        }
        
        // Fetch all ateliers from the repository
        if($security->getUser()){$formateur = $formateurRepository->findOneBy(['user'=>$security->getUser()->getId()]);
        } else {
            $formateur = null;
        }

        return $this->render('index.html.twig', [
            "formateur" => $formateur
        ]);
    }

    #[Route('/email',name: 'mail')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->render('base.html.twig', [
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