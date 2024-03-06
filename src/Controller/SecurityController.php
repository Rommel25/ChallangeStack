<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\User;
use App\Form\FirstConnexionType;
use App\Form\FormateurType;
use App\Form\LoginType;
use App\Form\UserCreateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    // Injection de dépendance par le constructeur
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    //Login fonctionne -> User vont vers index ou profile et admin va vers
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        $userrepo = $this->entityManager->getRepository(User::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $mail = $formData->getEmail();
            $user = $userrepo->findOneBy(["email" => $mail]);
//            dd(($formData->getPassword()) == $user->getPassword());
            if (($formData->getPassword()) == $user->getPassword()) {
                $roles = [];
                if (in_array('ADMIN', $user->getRoles())) {
                    $roles[] = 'ADMIN';
                }

                if (in_array('USER', $user->getRoles())) {
                    $roles[] = 'USER';
                }
                if (in_array('TEACHER', $user->getRoles())) {
                    $roles[] = 'TEACHER';
                }
//                $roles[] = 'USER';

                $token = new UsernamePasswordToken($user, 'firewall' , $roles);
                $this->container->get('security.token_storage')->setToken($token);
                return $this->redirectToRoute('app_atelier');
            }
        }
        // render login form
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/signin', name: 'signin')]
    public function signin(): Response
    {
        // $form = $this->createForm(SigninType::class);

        return $this->render('security/signin.html.twig');
    }

    #[Route(path: '/premiereconnexion/{id}', name: 'firstco', methods: ['GET', 'POST'])]
    public function firstConnexion($id, Request $request): Response
    {
        $userrepo = $this->entityManager->getRepository(User::class);
        $user = $userrepo->findOneBy(['token'=>$id]);
        $form = $this->createForm(UserCreateType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $formData = $form->getData();
            if($formData->getPassword() == $formData->getPlainpassword()){
                $user->setPassword($formData->getPassword());
                $roles = [];

                if (in_array('USER',$user->getRoles())) {
                    $roles[] = 'USER';
                }

                if ( in_array('TEACHER',$user->getRoles())) {
                    $roles[] = 'TEACHER';
                }
//                $roles[] = 'ROLE_USER';

                $token = new UsernamePasswordToken($user, 'firewall' , $roles);

                $this->container->get('security.token_storage')->setToken($token);
                $user->setToken('');
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->redirectToRoute('app_atelier');
            }

        }
        return $this->render('security/firstConnexion.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
        ]);
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $formateur = new Formateur();
        $form = $this->createForm(FormateurType::class, $formateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formateur->getUser()->setPassword($formateur->getUser()->getPassword());
            $formateur->getUser()->setRoles(["TEACHER"]);
            $this->entityManager->persist($formateur);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/signin.html.twig', [
            'form' => $form->createView(),
        ]);
    }




}