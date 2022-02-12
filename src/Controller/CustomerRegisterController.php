<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CustomerRegisterController extends AbstractController
{
    #[Route('/customer/request', name: 'customerRequest')]
    public function request(MailerInterface $mailer): Response
    {
        if(isset($_POST['customerRequestBtn'])){

        }

        return $this->render('customer_register/request.html.twig', [
            'controller_name' => 'CustomerRegisterController',
        ]);
    }
}
