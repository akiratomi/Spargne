<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Document;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AdvisorController extends AbstractController
{
/* ---------------------------- Customer Request ---------------------------- */
    
    #[Route('/advisor/customerRequests', name: 'customerRequests')]
    public function customerRequests(): Response
    {
        $customerRequests = $this->getDoctrine()->getRepository(User::class)->findByRole('CUSTOMER_REQUEST');

        return $this->render('advisor/customerRequests.html.twig', [
            'customerRequests' => $customerRequests,
        ]);
    }

    #[Route('/advisor/customerRequests/{id}', name: 'customerRequestsById')]
    public function customerRequestsById(int $id): Response
    {
        $customerRequest = null;
        $customerRequests = $this->getDoctrine()->getRepository(User::class)->findByRole('CUSTOMER_REQUEST');
        foreach ($customerRequests as $key => $value) {
            if($id == $value->getId()){
                $customerRequest = $value;
            }
        }

        return $this->render('advisor/customerRequestsById.html.twig', [
            'file_directory_twig' => $this->getParameter('file_directory_twig'),
            'customerRequests' => $customerRequests,
            'customerRequest' => $customerRequest,
        ]);
    }

    #[Route('/advisor/confirmCustomerRequest/{id}', name: 'confirmCustomerRequest')]
    public function confirmCustomerRequest(int $id): Response
    {
        $customerRequest = $this->getDoctrine()->getRepository(User::class)->find($id);
        $customerRequest->setAdvisor($this->getUser());

        $customerRequest->setRoles(array('ROLE_CUSTOMER'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($customerRequest);
        $em->flush();

        return $this->redirectToRoute('customerRequests');
    }
    
    #[Route('/advisor/customerRequestRefuseDocument/{id}/{type}', name: 'customerRequestRefuseDocument')]
    public function customerRequestRefuseDocument(int $id, int $type, MailerInterface $mailer): Response
    {
        $documentToRefuse = $this->getDoctrine()->getRepository(Document::class)->find($id);
        if($type == 1){
            $user = $documentToRefuse->getUser1();
            $user->setIdCardFront(null);
        }elseif($type == 2){
            $user = $documentToRefuse->getUser2();
            $user->setIdCardBack(null);
        }elseif($type == 3){
            $user = $documentToRefuse->getUser3();
            $user->setProofOfAddress(null);
        }
        
        if($user != null && $documentToRefuse != null){
            $body = '<p>A document of your Spargne request has been refused please upload a new one.</p>
            <p>Document type : '.$documentToRefuse->getType()->getName().'</p>
            <p>Link to connect : <a href="'.$this->getParameter('website_url').'login">S\'Pargne Personal Space Login</a></p>';
    
            $html = $this->generateHtml($body);
    
            if($type == 1){
                unlink($this->getParameter('file_directory')."idCardFront/".$documentToRefuse->getName().".pdf");
            }elseif($type == 2){
                unlink($this->getParameter('file_directory')."idCardBack/".$documentToRefuse->getName().".pdf");
            }elseif($type == 3){
                unlink($this->getParameter('file_directory')."proofOfAddress/".$documentToRefuse->getName().".pdf");
            }
    
            $email = (new Email())
            ->from('victor.robin@epsi.fr')
            ->to($user->getEmail())
            ->subject('S\'Pargne document refused')
            ->html($html);
    
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $this->render('customer_register/request.html.twig', [
                    'controller_name' => 'CustomerRegisterController',
                    'error' => $e,
                ]);
            }

            
    
            $em = $this->getDoctrine()->getManager();
            $em->remove($documentToRefuse);
            $em->persist($user);
            $em->flush();
    
            return $this->redirectToRoute('customerRequestsById', array('id'=>$user->getId()) );
        }else{
            return $this->redirectToRoute('customerRequests');
        }
        
    }

/* ---------------------------- Functions ---------------------------- */

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