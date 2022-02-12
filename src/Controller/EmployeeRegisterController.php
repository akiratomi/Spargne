<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mail;
use App\Entity\Hire;
use App\Entity\HireStatus;
use App\Entity\HireType;
use App\Entity\User;

use App\Form\AddUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeRegisterController extends AbstractController
{
    #[Route('/gettingHire/advisor/{token}', name: 'advisor_register')]
    public function advisor(string $token, Request $request, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $status = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Send'));
        $acceptedStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Accepted'));
        $refusedStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Refused'));

        $typeAdvisor = $this->getDoctrine()->getRepository(HireType::class)->findOneBy(array('name'=>'Advisor'));

        $test = $this->getDoctrine()->getRepository(Hire::class)->findOneBy(array('token'=>$token, 'status'=>$status, 'type'=>$typeAdvisor));

        if($test != null){
            $user = new User();
            $form = $this->createForm(AddUserType::class, $user);

            if ($request->isMethod('POST')) { 
                $form->handleRequest($request); 
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setRoles(array('ROLE_ADVISOR'));
                    $user->setRegisterDate(new \DateTime(date('now')));
                    $user->setProfilPicture("TEMP");

                    $uid = $this->generateUID(10);
                    $test_user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('uuid'=>$uid));

                    while($test_user != null){
                        $uid = $this->generateUID(10);
                        $test_user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('uuid'=>$uid));
                    }

                    $user->setUuid($uid);

                    $password = $this->generatePassword(8);

                    $user->setPassword($passwordHasher->hashPassword($user,$password));
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $test->setStatus($acceptedStatus);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($test);
                    $em->flush();

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
                    <body>
                    <p>Your account as advisor in S\'Pargne company has been created.</p>
                    <p>Here is your login details to connect at your personal space.</p>
                    <p>ID : '.$uid.'</p>
                    <p>Password : '.$password.'</p>
                    <p>Keep them secret.</p>
                    <p>Link to connect : <a href="'.$this->getParameter('website_url').'login">S\'Pargne Personal Space Login</a></p>
                    </body>
                    </html>
                    ';
        
                    $email = (new Email())
                    ->from('victor.robin@epsi.fr')
                    ->to($user->getEmail())
                    ->subject('S\'Pargne Congratulation')
                    ->html($html);
        
                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        return $this->render('employee_register/advisor.html.twig', [
                            'controller_name' => 'EmployeeRegisterController',
                            'error' => true,
                            'errorTag' => 'Email address not valid',
                        ]);
                    }

                    return $this->render('employee_register/advisor.html.twig', [
                        'controller_name' => 'EmployeeRegisterController',
                        'error' => true,
                        'errorTag' => 'Congratulation your personal space has been created. Please check your emails to find your login details.',
                    ]);
                }
            } 
        }else{
            return $this->render('employee_register/advisor.html.twig', [
                'controller_name' => 'EmployeeRegisterController',
                'error' => true,
                'errorTag' => 'Invalid Token',
            ]);
        }
        return $this->render('employee_register/advisor.html.twig', [
            'controller_name' => 'EmployeeRegisterController',
            'form' => $form->createView(),
            'hire' => $test,
        ]);
    }

    #[Route('/gettingHire/secretary/{token}', name: 'secretary_register')]
    public function secretary(string $token, Request $request, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $status = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Send'));
        $acceptedStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Accepted'));
        $refusedStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Refused'));

        $typeSecretary = $this->getDoctrine()->getRepository(HireType::class)->findOneBy(array('name'=>'Secretary'));

        $test = $this->getDoctrine()->getRepository(Hire::class)->findOneBy(array('token'=>$token, 'status'=>$status, 'type' => $typeSecretary));

        if($test != null){
            $user = new User();
            $form = $this->createForm(AddUserType::class, $user);

            if ($request->isMethod('POST')) { 
                $form->handleRequest($request); 
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setRoles(array('ROLE_SECRETARY'));
                    $user->setRegisterDate(new \DateTime(date('now')));
                    $user->setProfilPicture("TEMP");

                    $uid = $this->generateUID(10);
                    $test_user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('uuid'=>$uid));

                    while($test_user != null){
                        $uid = $this->generateUID(10);
                        $test_user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('uuid'=>$uid));
                    }

                    $user->setUuid($uid);

                    $password = $this->generatePassword(8);

                    $user->setPassword($passwordHasher->hashPassword($user,$password));
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $test->setStatus($acceptedStatus);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($test);
                    $em->flush();

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
                    <body>
                    <p>Your account as secretary in S\'Pargne company has been created.</p>
                    <p>Here is your login details to connect at your personal space.</p>
                    <p>ID : '.$uid.'</p>
                    <p>Password : '.$password.'</p>
                    <p>Keep them secret.</p>
                    <p>Link to connect : <a href="'.$this->getParameter('website_url').'login">S\'Pargne Personal Space Login</a></p>
                    </body>
                    </html>
                    ';
        
                    $email = (new Email())
                    ->from('victor.robin@epsi.fr')
                    ->to($user->getEmail())
                    ->subject('S\'Pargne Congratulation')
                    ->html($html);
        
                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        return $this->render('employee_register/secretary.html.twig', [
                            'controller_name' => 'EmployeeRegisterController',
                            'error' => true,
                            'errorTag' => 'Email address not valid',
                        ]);
                    }


                    return $this->render('employee_register/secretary.html.twig', [
                        'controller_name' => 'EmployeeRegisterController',
                        'error' => true,
                        'errorTag' => 'Congratulation your personal space has been created. Please check your emails to find your login details.',
                    ]);
                }
            } 
        }else{
            return $this->render('employee_register/secretary.html.twig', [
                'controller_name' => 'EmployeeRegisterController',
                'error' => true,
                'errorTag' => 'Invalid Token',
            ]);
        }
        return $this->render('employee_register/secretary.html.twig', [
            'controller_name' => 'EmployeeRegisterController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/gettingHire/declineHire/{id}', name: 'declineHire')]
    public function declineHire(int $id): Response
    {
        $refusedStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Refused'));
        $hire = $this->getDoctrine()->getRepository(Hire::class)->find($id);

        $hire->setStatus($refusedStatus);

        $em = $this->getDoctrine()->getManager();
        $em->persist($hire);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    public function generateUID($length)
    {
        $chars = '0123456789';
        $chars_length = strlen($chars);
        $uid = '';
        for ($i = 0; $i < $length; $i++)
        {
            $uid .= $chars[rand(0, $chars_length - 1)];
        }
        return $uid;
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
