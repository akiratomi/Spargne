<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\ModifyProfil;
use App\Entity\ModifyProfilType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function profil(): Response
    {
        $user = $this->getUser();

        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }

    #[Route('/profil/modifyEmail', name: 'modifyEmail')]
    public function modifyEmail(MailerInterface $mailer): Response
    {
        if(isset($_POST['modifyEmailButton'])){
            $user = $this->getUser();
            $token = $this->generateToken(10);
            $newEmail = $_POST['email'];
            $modifyProfil = new ModifyProfil();
            $modifyProfilTypeEmail = $this->getDoctrine()->getRepository(ModifyProfilType::class)->findOneBy(array('name'=>'email'));
            $modifyProfil->setData($newEmail);
            $modifyProfil->setToken($token);
            $modifyProfil->setUser($user);
            $modifyProfil->setType($modifyProfilTypeEmail);

            $em = $this->getDoctrine()->getManager();
            $em->persist($modifyProfil);
            $em->flush();

            $body = '<p>Please verify your new email.</p>
            <a href="'.$this->getParameter('website_url').'profil/modifyEmail/'.$user->getId().'/'.$token.'">Verify</a>';

            $html = $this->generateHtml($body);

            $email = (new Email())
            ->from('victor.robin@epsi.fr')
            ->to($newEmail)
            ->subject('S\'Pargne verify')
            ->html($html);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $this->render('profil/profil.html.twig', [
                    'controller_name' => 'ProfilController',
                    'message' => 'An error occured.',
                ]);
            }

            return $this->render('profil/profil.html.twig', [
                'controller_name' => 'ProfilController',
                'message' => 'An email has been sent to confirm new email.',
            ]);
        }
        return $this->redirectToRoute('accueil');
        
    }

    #[Route('/profil/modifyEmail/{id}/{token}', name: 'modifyEmailVerify')]
    public function modifyEmailVerify(int $id, string $token): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user != null){
            $modifyProfilTypeEmail = $this->getDoctrine()->getRepository(ModifyProfilType::class)->findOneBy(array('name'=>'email'));
            $modifyProfil = $this->getDoctrine()->getRepository(ModifyProfil::class)->findOneBy(array('user'=>$user, 'token'=>$token, 'type'=> $modifyProfilTypeEmail));
            if($modifyProfil != null){
                $user->setEmail($modifyProfil->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->remove($modifyProfil);
                $em->flush();

                $this->getUser()->setEmail($modifyProfil->getData());

                return $this->render('profil/profil.html.twig', [
                    'controller_name' => 'ProfilController',
                    'message' => 'Email modified.',
                ]);
            }
        }
        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
            'message' => 'Invalid Token.',
        ]);
    }

    #[Route('/profil/modifyPhone', name: 'modifyPhone')]
    public function modifyPhone(): Response
    {
        if(isset($_POST['modifyPhoneButton'])){
            $user = $this->getUser();
            $newPhone = $_POST['phone'];
            $user->setPhoneNumber($newPhone);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('profil/profil.html.twig', [
                'controller_name' => 'ProfilController',
                'message' => 'Phone number modified.',
            ]);
        }
        return $this->redirectToRoute('accueil');
    }

    #[Route('/profil/modifyMdp', name: 'modifyMdp')]
    public function modifyMdp(UserPasswordHasherInterface $passwordHasher): Response
    {
        if(isset($_POST['modifyMdpBtn'])){
            if(strlen($_POST['password']) == 8){
                $user = $this->getUser();

                $user->setPassword($passwordHasher->hashPassword($user, $_POST['password']));
                $user->setFirstMdp(false);
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->render('profil/profil.html.twig', [
                    'controller_name' => 'ProfilController',
                    'message' => 'Your password has been correclty modified.',
                ]);
            }else{
                return $this->render('profil/modifyMdp.html.twig', [
                    'controller_name' => 'ProfilController',
                    'error' => 'Your password must contain at least 8 caracters.',
                ]);
            }
        }

        return $this->render('profil/modifyMdp.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    public function generateToken($length)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars_length = strlen($chars);
        $token = '';
        for ($i = 0; $i < $length; $i++)
        {
            $token .= $chars[rand(0, $chars_length - 1)];
        }
        return $token;
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
}
