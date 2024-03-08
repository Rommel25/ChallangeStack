<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FormateurRepository;
use App\Repository\CreneauRepository;
use App\Repository\ClasseRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/cours')]
class CoursController extends AbstractController
{
   #[Route('/', name: 'app_cours_index', methods: ['GET'])]
   public function index(CoursRepository $coursRepository,FormateurRepository $formateurRepository ,EntityManagerInterface $entityManager, Security $security): Response
   {
       $formateur = $formateurRepository->findOneBy(['user'=>$security->getUser()->getId()]);
    if ($formateur){

    
       $difficultiesData = $entityManager
           ->createQueryBuilder()
           ->select('c.difficulte', 'c.titre','c.description','c.objectif','c.duree','c.id')
           ->from('App\Entity\Cours', 'c')
           ->where('c.formateur = '. $formateur->getId())
           ->groupBy('c.difficulte, c.titre','c.description','c.objectif','c.duree','c.id')
           ->getQuery()
           ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            $difficultiesData = $entityManager
            ->createQueryBuilder()
            ->select('c.difficulte', 'c.titre','c.description','c.objectif','c.duree','c.id')
            ->from('App\Entity\Cours', 'c')
            ->groupBy('c.difficulte, c.titre','c.description','c.objectif','c.duree','c.id')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);}
       // Organiser les données par difficulté et construire un tableau "data" pour chaque difficulté
       $groupedData = [];
       foreach ($difficultiesData as $row) {
           $difficulte = $row['difficulte'];
           $titre = $row['titre'];
           $description = $row['description'];
           $objectif = $row['objectif'];
           $duree = $row['duree'];
           $id = $row['id'];

           if (!isset($groupedData[$difficulte])) {
               $groupedData[$difficulte] = [
                   'difficulte' => $difficulte,
                   'data' => [],
               ];
           }

           $groupedData[$difficulte]['data'][] = [
                'titre' => $titre,
                'description' => $description,
                'objectif' => $objectif,
                'duree' => $duree,
                'id' => $id
            ];
       }

       $result = array_values($groupedData);

       return $this->render('cours/index.html.twig', [
           'cours' => $coursRepository->findAll(),
           'difficultiesData' => $result,
       ]);
   }

    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $ressourceFile */
            $ressourceFile = $form->get('ressource')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($ressourceFile) {
                $originalFilename = pathinfo($ressourceFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$ressourceFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ressourceFile->move(
                        $this->getParameter('cours_resources_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $cour->setRessource($newFilename);
            }
            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour,CreneauRepository $creneauRepository, ClasseRepository $classeRepository, CoursRepository $coursRepository): Response
    {
//        $classesByCours = $creneauRepository->findBy(['cours'=>$cour]);
//        $classesByCours = $classeRepository->findBy(['cours'=>$cour->getId()]);
//        dd($classesByCours);
//        $responses = [];
//        foreach ($classesByCours as $classe){
//            $responses[] = $classeRepository->findBy(['id'=>$classe->getClasse()->getId()]);
//        };
        $classes = $cour->getClasses();

        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'classesByCours' => $classes
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $ressourceFile */
            $ressourceFile = $form->get('ressource')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($ressourceFile) {
                $originalFilename = pathinfo($ressourceFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$ressourceFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ressourceFile->move(
                        $this->getParameter('cours_resources_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $cour->setRessource($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/telecharger_pdf/{id}', name: 'telecharger_pdf', methods: ['GET'])]
    public function telechargerPdf(Request $request, Cours $cour): Response
    {

        if (!$cour) {
            throw $this->createNotFoundException('Entité non trouvée');
        }

        $pdfPath = $this->getParameter('cours_resources_directory') . '/' . $cour->getRessource();

        // Vérifiez si le fichier PDF existe
        if (!file_exists($pdfPath)) {
            throw $this->createNotFoundException('Fichier PDF non trouvé ' . $pdfPath);
        }

        // Renvoyer le fichier PDF comme réponse HTTP
        return $this->file($pdfPath);
    }
}
