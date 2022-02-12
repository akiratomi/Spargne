<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Entity\Mail;
use App\Entity\Hire;
use App\Entity\HireStatus;
use App\Entity\HireType;
use App\Entity\User;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PersonnelController extends AbstractController
{
    #[Route('/personnel/hire/advisor', name: 'personnelHireAdvisor')]
    public function personnelHireAdvisor(MailerInterface $mailer): Response
    {
        if(isset($_POST['hireAdvisorBtn']))
        {
            $hireEmail = $_POST['email'];
            $token = $this->generateToken(10);
            $test = $this->getDoctrine()->getRepository(Hire::class)->findBy(array('token'=>$token, 'status'=>false));
            $typeAdvisor = $this->getDoctrine()->getRepository(HireType::class)->findOneBy(array('name'=>'Advisor'));
            while($test != null){
                $token = $this->generateToken(10);
                $test = $this->getDoctrine()->getRepository(Hire::class)->findBy(array('token'=>$token, 'status'=>false));
            }

            $newHire = new Hire();
            $newHire->setToken($token);
            $newHire->setDate(new \DateTime(date('now')));
            $newHire->setEmail($hireEmail);
            $newHire->setType($typeAdvisor);
            $status = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Send'));
            $newHire->setStatus($status);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newHire);
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
            <p>You have been hire in S\'pargne Bank. Please click the link to create your advisor account.</p>
            <a href="'.$this->getParameter('website_url').'gettingHire/advisor/'.$token.'">Register</a>
            </body>
            </html>
            ';

            $email = (new Email())
            ->from('victor.robin@epsi.fr')
            ->to($hireEmail)
            ->subject('S\'Pargne Job')
            ->html($html);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $this->render('personnel/personnelHireAdvisor.html.twig', [
                    'controller_name' => 'PersonnelController',
                    'error' => $e,
                ]);
            }
        }
        return $this->render('personnel/personnelHireAdvisor.html.twig', [
            'controller_name' => 'PersonnelController',
        ]);
    }

    #[Route('/personnel/hire/advisor/delete/{id}', name: 'personnelHireAdvisorDelete')]
    public function personnelHireAdvisorDelete(int $id): Response
    {
        $hire = $this->getDoctrine()->getRepository(Hire::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($hire);
        $em->flush();

        return $this->redirectToRoute('personnelAdvisorList');
    }

    #[Route('/personnel/hire/secretary', name: 'personnelHireSecretary')]
    public function personnelHireSecretary(MailerInterface $mailer): Response
    {
        if(isset($_POST['hireSecretaryBtn']))
        {
            $hireEmail = $_POST['email'];
            $token = $this->generateToken(10);
            $test = $this->getDoctrine()->getRepository(Hire::class)->findBy(array('token'=>$token, 'status'=>false));
            $typeSecretary = $this->getDoctrine()->getRepository(HireType::class)->findOneBy(array('name'=>'Secretary'));
            while($test != null){
                $token = $this->generateToken(10);
                $test = $this->getDoctrine()->getRepository(Hire::class)->findBy(array('token'=>$token, 'status'=>false));
            }

            $newHire = new Hire();
            $newHire->setToken($token);
            $newHire->setDate(new \DateTime(date('now')));
            $newHire->setEmail($hireEmail);
            $newHire->setType($typeSecretary);
            $status = $this->getDoctrine()->getRepository(HireStatus::class)->findOneBy(array('name'=>'Send'));
            $newHire->setStatus($status);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newHire);
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
            <p>You have been hire in S\'pargne Bank. Please click the link to create your Secretary account.</p>
            <a href="'.$this->getParameter('website_url').'gettingHire/secretary/'.$token.'">Register</a>
            </body>
            </html>
            ';

            $email = (new Email())
            ->from('victor.robin@epsi.fr')
            ->to($hireEmail)
            ->subject('S\'Pargne Job')
            ->html($html);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $this->render('personnel/personnelHireSecretary.html.twig', [
                    'controller_name' => 'PersonnelController',
                    'error' => $e,
                ]);
            }
        }

        return $this->render('personnel/personnelHireSecretary.html.twig', [
            'controller_name' => 'PersonnelController',
        ]);
    }

    #[Route('/personnel/hire/secretary/delete/{id}', name: 'personnelHireSecretaryDelete')]
    public function personnelHireSecretaryDelete(int $id): Response
    {
        $hire = $this->getDoctrine()->getRepository(Hire::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($hire);
        $em->flush();

        return $this->redirectToRoute('personnelSecretaryList');
    }

    #[Route('/personnel/list/advisor', name: 'personnelAdvisorList')]
    public function personnelAdvisorList(): Response
    {
        $advisorType = $this->getDoctrine()->getRepository(HireType::class)->findBy(array('name'=>'advisor'));
        $sendStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findBy(array('name'=>'Send'));
        $refusedStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findBy(array('name'=>'Refused'));
        $hires = $this->getDoctrine()->getRepository(Hire::class)->findBy(array('type'=>$advisorType, 'status'=>$sendStatus, 'status'=>$refusedStatus));

        $advisors = $this->getDoctrine()->getRepository(User::class)->findByRole('ADVISOR');
        return $this->render('personnel/personnelAdvisorList.html.twig', [
            'controller_name' => 'PersonnelController',
            'advisors' => $advisors,
            'hires' => $hires,
        ]);
    }

    #[Route('/personnel/list/secretary', name: 'personnelSecretaryList')]
    public function personnelSecretaryList(): Response
    {
        $secretaryType = $this->getDoctrine()->getRepository(HireType::class)->findBy(array('name'=>'secretary'));
        $sendStatus = $this->getDoctrine()->getRepository(HireStatus::class)->findBy(array('name'=>'Send'));
        $hires = $this->getDoctrine()->getRepository(Hire::class)->findBy(array('type'=>$secretaryType, 'status'=>$sendStatus));

        $secretarys = $this->getDoctrine()->getRepository(User::class)->findByRole('SECRETARY');

        return $this->render('personnel/personnelSecretaryList.html.twig', [
            'controller_name' => 'PersonnelController',
            'secretarys' => $secretarys,
            'hires' => $hires,
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
}
