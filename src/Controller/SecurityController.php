<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\AddUserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(AddUserType::class, $user);

        if ($request->isMethod('POST')) { 
            $form->handleRequest($request); 
            if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(array('ROLE_USER'));
            $user->setUuid('1234');
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Inscription réussie');
            return $this->redirectToRoute('authentification');
            }
        } 

        return $this->render('security/register.html.twig', [
            'controller_name' => 'AuthentificationController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forgottenPassword', name: 'forgottenPassword')]
    public function forgottenPassword(UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
            if(isset($_POST['forgottenPasswordBtn'])){
                $uuid = $_POST['uuid'];
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('uuid'=>$uuid));
                if($user != null){
                    $password = $this->generatePassword(8);

                    $user->setPassword($passwordHasher->hashPassword($user,$password));
                    $user->setFirstMdp(true);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $body = '<p>Your password has been re-generate.</p>
                    <p>Please login to your personal space to modify it.</p>
                    <p>Your new password : '.$password.'.</p>
                    <p>Link to connect : <a href="'.$this->getParameter('website_url').'login">S\'Pargne Personal Space Login</a></p>';
        
                    $html = $this->generateHtml($body);
        
                    $email = (new Email())
                    ->from('victor.robin@epsi.fr')
                    ->to($user->getEmail())
                    ->subject('S\'Pargne recover')
                    ->html($html);
        
                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        return $this->render('security/forgottenPassword.html.twig', [
                            'controller_name' => 'ProfilController',
                            'error' => 'An error occured.',
                        ]);
                    }

                    return $this->render('security/forgottenPassword.html.twig', [
                        'controller_name' => 'ProfilController',
                        'error' => 'Your password has been re-generated. An email has been sent to your email address.',
                    ]);
                }else{
                    return $this->render('security/forgottenPassword.html.twig', [
                        'controller_name' => 'AuthentificationController',
                        'error' => 'Id not found'
                    ]);
                }
            }

        return $this->render('security/forgottenPassword.html.twig', [
            'controller_name' => 'AuthentificationController',
        ]);
    }

    public function generateHtml($body)
    {
        $html = '
            <html lang="fr">
            <head>
            <meta charset="utf-8">
            <title>Titre de la page</title>
            <link rel=\_"stylesheet" href="style.css">
            <script src="script.js"></script>
            </head>
            <style>
            </style>
            <body>'.$body.'</body>
            </html>
            ';

        return($html);
    }

    public function generatePassword($length)
    {
        $chars = '0123456789';
        $chars_length = strlen($chars);
        $password = '';
        for ($i = 0; $i < $length; $i++)
        {
            $password .= $chars[rand(0, $chars_length - 1)];
        }
        return $password;
    }
}
