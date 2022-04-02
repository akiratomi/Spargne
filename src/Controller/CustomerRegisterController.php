<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\CustomerRequest;
use App\Entity\User;
use App\Entity\Document;
use App\Entity\DocumentType;

use App\Form\AddUserType;
use App\Form\AddDocumentType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class CustomerRegisterController extends AbstractController
{
    #[Route('/customer/request', name: 'customerRequest')]
    public function request(MailerInterface $mailer): Response
    {
        if(isset($_POST['customerRequestBtn'])){
            $email = $_POST['email'];
            $token = $this->generateToken(5);


            $newCustomerRequest = new CustomerRequest();
            $newCustomerRequest->setEmail($email);
            $newCustomerRequest->setToken($token);
            $newCustomerRequest->setVerified(false);
            $date = new \DateTime(date("m.d.y"));
            $newCustomerRequest->setRequestDate($date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newCustomerRequest);
            $em->flush();

            $body = '<p>Please verify your email.</p>
            <a href="'.$this->getParameter('website_url').'customer/request/'.$newCustomerRequest->getId().'/'.$token.'">Verify</a>';

            $html = $this->generateHtml($body);

            $email = (new Email())
            ->from('victor.robin@epsi.fr')
            ->to($email)
            ->subject('S\'Pargne verify')
            ->html($html);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $this->render('customer_register/request.html.twig', [
                    'controller_name' => 'CustomerRegisterController',
                    'error' => $e,
                ]);
            }
            return $this->render('customer_register/request.html.twig', [
                'controller_name' => 'CustomerRegisterController',
                'advice' => 'You may have received an email. Please check your emails to verify it.',
            ]);
        }

        return $this->render('customer_register/request.html.twig', [
            'controller_name' => 'CustomerRegisterController',
        ]);
    }

    #[Route('/customer/request/{id}/{token}', name: 'customerRequestVerified')]
    public function requestVerified(int $id, String $token, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, Request $request): Response
    {
        $customerRequest = $this->getDoctrine()->getRepository(CustomerRequest::class)->findOneBy(array('id'=>$id, 'token'=>$token));
        
        if($customerRequest != null){
            if($customerRequest->getVerified() == false){
                $customerRequest->setVerified(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($customerRequest);
                $em->flush();
            }
            $user = new User();
            $form = $this->createForm(AddUserType::class, $user, array('emailUser'=>$customerRequest->getEmail()));
            if ($request->isMethod('POST')) { 
                $form->handleRequest($request); 
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setRoles(array('ROLE_CUSTOMER_REQUEST'));
                    $date = new \DateTime(date("m.d.y"));
                    $user->setRegisterDate($date);
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
                    $user->setFirstMdp(true);

                    


                    $body = '<p>Your account has been created. You must wait until it get confirm.</p>
                    <p>To confirm your register you must add those next documents :.</p>
                    <p>- Double side Identity card.</p>
                    <p>- A proof of address.</p>
                    <p>Here are your login details to track and complete your register.</p>
                    <p>ID : '.$uid.'.</p>
                    <p>PassWord : '.$password.'.</p>
                    <p>Link to connect : <a href="'.$this->getParameter('website_url').'login">S\'Pargne Personal Space Login</a>.</p>';

                    $html = $this->generateHtml($body);

                    $email = (new Email())
                    ->from('victor.robin@epsi.fr')
                    ->to($user->getEmail())
                    ->subject('S\'Pargne Congratulation')
                    ->html($html);
        
                    try {
                        $mailer->send($email);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($user);
                        $em->flush();

                        $em = $this->getDoctrine()->getManager();
                        $em->remove($customerRequest);
                        $em->flush();

                        return $this->render('customer_register/register.html.twig', [
                            'error' => 'Congratulation, your register request has been sent. Please check your email to find out how to confirm your account',
                        ]);
                    } catch (TransportExceptionInterface $e) {
                        return $this->render('customer_register/register.html.twig', [
                            'customerRequest' => $customerRequest,
                            'form' => $form->createView(),
                            'error' => 'An error has occured',
                        ]);
                    }
                }
            }

            return $this->render('customer_register/register.html.twig', [
                'controller_name' => 'CustomerRegisterController',
                'customerRequest' => $customerRequest,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('customer_register/register.html.twig', [
            'controller_name' => 'CustomerRegisterController',
            'error' => 'Invalid token',
        ]);
    }

    #[Route('/customer/request/profil', name: 'customerRequestProfil')]
    public function customerRequestProfil(MailerInterface $mailer, Request $request): Response
    {
        $user = $this->getUser();

        $document = new Document();
        $document = new Document();
        $document = new Document();

        $idCardFrontType =  $this->getDoctrine()->getRepository(DocumentType::class)->find(1);
        $idCardBackType = $this->getDoctrine()->getRepository(DocumentType::class)->find(2);
        $profOfAddressType = $this->getDoctrine()->getRepository(DocumentType::class)->find(3);

        $formIdCardFront = $this->createForm(AddDocumentType::class, $document, array('type'=>$idCardFrontType));
        $formIdCardBack = $this->createForm(AddDocumentType::class, $document, array('type'=>$idCardBackType));
        $formProofOfAddress = $this->createForm(AddDocumentType::class, $document, array('type'=>$profOfAddressType));

        

        if ($request->isMethod('POST')) { 
            $formIdCardFront->handleRequest($request); 
            if ($formIdCardFront->isSubmitted() && $formIdCardFront->isValid()) {
                $fichierPhysique = $document->getName();
                $ex = '';

                if($fichierPhysique->guessExtension()!=null){
                    $ex = $fichierPhysique->guessExtension();
                }
                if($ex == "pdf"){
                    $document->setEx("pdf");
                    $document->setName(md5(uniqid()));
                    if($document->getType() == $idCardFrontType){
                        $user->setIdCardFront($document);

                        try{
                            $fichierPhysique->move($this->getParameter('file_directory')."idCardFront/", $document->getName().".pdf");
    
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($document);
                            $em->flush();
                            
                        }catch(FileException $e){
                            $this->addFlash('notice','Erreur de création');
                        }
                    }elseif($document->getType() == $idCardBackType){
                        $user->setIdCardBack($document);

                        try{
                            $fichierPhysique->move($this->getParameter('file_directory')."idCardBack/", $document->getName().".pdf");
    
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($document);
                            $em->flush();
                            
                        }catch(FileException $e){
                            $this->addFlash('notice','Erreur de création');
                        }
                    }elseif($document->getType() == $profOfAddressType){
                        $user->setProofOfAddress($document);

                        try{
                            $fichierPhysique->move($this->getParameter('file_directory')."proofOfAddress/", $document->getName().".pdf");
    
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($document);
                            $em->flush();
                            
                        }catch(FileException $e){
                            $this->addFlash('notice','Erreur de création');
                        }
                    }

                    
                }
                return $this->redirectToRoute('customerRequestProfil');
            }
        }


        return $this->render('customer_register/profil.html.twig', [
            'controller_name' => 'customerRequestProfilController',
            'user'=>$user,
            'file_directory_twig' => $this->getParameter('file_directory_twig'),
            'formIdCardFront' => $formIdCardFront->createView(),
            'formIdCardBack' => $formIdCardBack->createView(),
            'formProofOfAddress' => $formProofOfAddress->createView(),
        ]);
    }

    public function generateToken($length)
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars_length = strlen($chars);
        $token = '';
        for ($i = 0; $i < $length; $i++)
        {
            $token .= $chars[rand(0, $chars_length - 1)];
        }
        return $token;
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
