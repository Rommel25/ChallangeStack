<?php

namespace App\Controller;

use League\Csv\Reader;
use App\Entity\Eleve;
use App\Entity\Classe;
use App\Entity\User;
use App\Form\ClasseType;
use App\Form\CsvImportType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\EleveRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/classe')]
class ClasseController extends AbstractController
{
    #[Route('/', name: 'app_classe_index', methods: ['GET'])]
    public function index(ClasseRepository $classeRepository): Response
    {
        return $this->render('classe/index.html.twig', [
            'classes' => $classeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_classe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($classe->getEleves() as $eleve){
                $eleve->addClass($classe);
            }
            $entityManager->persist($classe);
            $entityManager->flush();

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classe_show', methods: ['GET'])]
    public function show(Classe $classe, EleveRepository $eleveRepository): Response
    {

        $eleves = $classe->getEleves();

        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
            'eleves' => $eleves
        ]);
    }

    #[Route('/{id}/edit', name: 'app_classe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classe $classe, EntityManagerInterface $entityManager, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($classe->getEleves() as $eleve){
                $eleve->addClass($classe);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        $formCsv = $this->createForm(CsvImportType::class);
        $formCsv->handleRequest($request);

        if ($formCsv->isSubmitted() && $formCsv->isValid()) {
            $csvFile = $formCsv->get('csv_file')->getData();
            
            if (($handle = fopen($csvFile->getPathname(), 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                    // $data est un tableau contenant les valeurs des colonnes pour une ligne
                    $firstName = $data[0];
                    $lastName = $data[1];
                    $email = $data[2];
                    
                    // Faites ce que vous voulez avec les données de chaque ligne ici
                    if ($userRepository->findOneBy(['email' => $email]) == null) {
                        $eleveUser = new User();
                        $eleveUser->setPrenom($firstName)
                                  ->setNom($lastName)
                                  ->setEmail($email)
                                  ->setPassword('')
                                  ->setPlainPassword('');
                        $uniqueId = uniqid();
                        $eleveUser->setToken($uniqueId);
                        $entityManager->persist($eleveUser);
    
                        $eleve = new Eleve();
                        $eleve->setUser($eleveUser);
                        $eleve->addClass($classe);
                        $entityManager->persist($eleve);
    
                        $email = (new Email())
                                ->from('support@academiaflow.com')
                                ->to($eleve->getUser()->getEmail())
                                ->subject('Inscription à AcademiaFlow')
                                ->text('Sending emails is fun again!')
                                ->html('<p>Set password here http://challenge.local/premiereconnexion/'.$uniqueId.'</p>');
    
                        $mailer->send($email);
                    }
                }
                $entityManager->flush();
                fclose($handle);
            } else {
                // Gérer l'erreur si le fichier ne peut pas être ouvert
                echo "Erreur lors de l'ouverture du fichier CSV.";
            }
            // Faire quelque chose avec les données du fichier CSV (par exemple, les enregistrer en base de données)
        }

        return $this->render('classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form,
            'formCsv' => $formCsv->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_classe_delete', methods: ['POST'])]
    public function delete(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($classe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
    }
}
