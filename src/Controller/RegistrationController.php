<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserRole;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use PharIo\Manifest\Email;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]

    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        Security $security,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            // On génère le JWT de l'utilisateur
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];
    
            $payload = [
                'user_id' => $user->getId()
            ];
    
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
    
            // On envoie un mail
            $mail->send(
                'no-reply@greenvillage',
                $user->getEmail(),
                'Activation de votre compte sur notre site !!!',
                'register',
                compact('user', 'token')
            );
    
            // Authentification avec Security
            //$security->login($user, authenticatorUtils);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
    
           // return $this->redirectToRoute('main');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $userRepository->find($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->isVerified()){
                $user->isVerified(true);
                $em->flush();
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_profile');
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_register');
    }

    #[Route('/verif/resend', name: 'resend_verif')]
    public function resendVerif( JWTService $jwt, SendMailService $mail,  EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->addFlash('error', "L'utilisateur n'est pas connecté ou introuvable.");
            return $this->redirectToRoute('app_login');
        }
    
        if ($user->isVerified()) {
            $this->addFlash('info', "Votre compte est déjà activé.");
            return $this->redirectToRoute('app_profile');
        }

        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // On envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Activation de votre compte sur le site e-commerce',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('profile_index');
    }
}