<?php

namespace App\Controller;

use App\Entity\Evaluation;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamenController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/createExamen', name: 'create_examen')]
    public function createExamen(Request $request): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(UserCreateType::class, $evaluation);
        $form->handleRequest($request);

        return $this->render('security/signin.html.twig');
    }

}