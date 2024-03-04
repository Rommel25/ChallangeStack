<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{

    #[Route('/', name: 'app_atelier')]
    public function index(): Response
    {
        // Fetch all ateliers from the repository

        return $this->render('base.html.twig', [
        ]);
    }

}