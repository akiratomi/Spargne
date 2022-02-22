<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Document;
use App\Entity\Account;
use App\Entity\AccountType;
use App\Entity\Beneficiary;

use App\Form\AccountFormType;

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

/* ---------------------------- Customer List ---------------------------- */

    #[Route('/advisor/customerList', name: 'customerList')]
    public function customerList(): Response
    {
        $customerAllList = $this->getDoctrine()->getRepository(User::class)->findByRole('CUSTOMER');
        $customerList = array();
        foreach ($customerAllList as $key => $value) {
            if($value->getAdvisor() == $this->getUser()){
                array_push($customerList, $value);
            }
        }

        return $this->render('advisor/customerList.html.twig', [
            'customerList' => $customerList,
        ]);
    }

    #[Route('/advisor/customer/{id}', name: 'advisorCustomer')]
    public function customer(int $id, Request $request): Response
    {
        $customer = $this->getDoctrine()->getRepository(User::class)->find($id);

        $newAccount = new Account();
        $formNewAccount = $this->createForm(AccountFormType::class, $newAccount);

        if(isset($_POST['transfertBtn'])){
            $amount = $_POST["amount"];
            $numberFrom = $_POST["numberFrom"];
            $numberTo = $_POST["numberTo"];
            $typeTo = $_POST["typeTo"];

            $canTryTransact = false;
            $canTransact = false;

            if($numberFrom != "" && $numberTo != "" && $typeTo != ""){
                $accountFrom = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('num'=>$numberFrom));
                $accountTo = null;
                if($typeTo == "1"){
                    $accountTo = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('num'=>$numberTo));
                }else if($typeTo == "2"){
                    $accountTo = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('iban'=>$numberTo));
                }

                if( $accountFrom != null && $accountTo != null){
                    if($accountFrom->getOverdraft() != null){
                        if( ($accountFrom->getBalance() - $amount) > (0 - $accountFrom->getOverdraft() ) ){
                            $canTryTransact = true;
                        }
                    }else{
                        if( ($accountFrom->getBalance() - $amount) > 0) {
                            $canTryTransact = true;
                        }
                    }

                    if($canTryTransact){
                        if($accountTo->getLimitBalance() != null){
                            $canTransact = true;
                        }else{
                            if( ($accountTo->getBalance() + $amount) < $accountTo->getLimitBalance()){
                                $canTransact = true;
                            }
                        }

                        if($canTransact){
                            $accountFrom->setBalance( $accountFrom->getBalance() - $amount );
                            $accountTo->setBalance( $accountTo->getBalance() + $amount );

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($accountFrom);
                            $em->persist($accountTo);
                            $em->flush();

                            return $this->redirectToRoute('advisorCustomer', array('id'=>$customer->getId()));
                        }else{
                            return $this->render('advisor/customer.html.twig', [
                                'customer' => $customer,
                                'formNewAccount' => $formNewAccount->createView(),
                                'errorTransact' => 'Error : The "destination" selected account limit balance would be overated by this transfert',
                            ]);
                        }
                    }else{
                        return $this->render('advisor/customer.html.twig', [
                            'customer' => $customer,
                            'formNewAccount' => $formNewAccount->createView(),
                            'errorTransact' => 'Error : The "from" selected account hasn\'t enough balance to complet transfert',
                        ]);
                    }
                }else{
                    return $this->render('advisor/customer.html.twig', [
                        'customer' => $customer,
                        'formNewAccount' => $formNewAccount->createView(),
                        'errorTransact' => 'Error : An error occured please try later',
                    ]);
                }
            }else{
                return $this->render('advisor/customer.html.twig', [
                    'customer' => $customer,
                    'formNewAccount' => $formNewAccount->createView(),
                    'errorTransact' => 'Error : Please select all field to complet transfert',
                ]);
            }

            
        }

        if ($request->isMethod('POST')) { 
            $formNewAccount->handleRequest($request); 
            if ($formNewAccount->isSubmitted() && $formNewAccount->isValid()) {

                $newAccount->setOwner($customer);

                $num = $this->generateUID(10);
                $test_num = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('num'=>$num));
                while($test_num != null){
                    $num = $this->generateUID(10);
                    $test_num = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('num'=>$num));
                }
                $newAccount->setNum($num);

                $iban = substr($customer->getCountry(), 0, 2);
                $iban = $iban."11";
                $iban = $iban.$num;
                $newAccount->setIban($iban);
                $newAccount->setBalance(0);
                $newAccount->setCreationDate(new \DateTime(date('now')));
                $newAccount->setLimitBalance($newAccount->getType()->getLimitBalance());
                $newAccount->setOverdraft($newAccount->getType()->getOverdraft());
                $newAccount->setRate($newAccount->getType()->getRate());

                $em = $this->getDoctrine()->getManager();
                $em->persist($newAccount);
                $em->flush();

                return $this->redirectToRoute('advisorCustomer', array('id'=>$customer->getId()));
            }

        }

        if(isset($_POST['beneficiaryAddBtn'])){
            $newBeneficiary = new Beneficiary();
            $test_acc = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('iban'=>$_POST['iban']));
            if($test_acc != null){
                $newBeneficiary->setAddedDate(new \DateTime(date('now')));
                $newBeneficiary->setOwner($customer);
                $newBeneficiary->setAccount($test_acc);
                $test_name = $this->getDoctrine()->getRepository(Beneficiary::class)->findOneBy(array('name'=>$_POST['name'], 'owner'=>$customer));
                if($test_name != null){
                    $newBeneficiary->setName($_POST['name']);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newBeneficiary);
                    $em->flush();

                    return $this->redirectToRoute('advisorCustomer', array('id'=>$customer->getId()));
                }else{
                    return $this->render('advisor/customer.html.twig', [
                        'customer' => $customer,
                        'formNewAccount' => $formNewAccount->createView(),
                        'error' => 'That beneficiary name albready exist',
                    ]);
                }
                

            }else{
                return $this->render('advisor/customer.html.twig', [
                    'customer' => $customer,
                    'formNewAccount' => $formNewAccount->createView(),
                    'error' => 'No account found for this iban',
                ]);
            }
        }

        

        return $this->render('advisor/customer.html.twig', [
            'customer' => $customer,
            'formNewAccount' => $formNewAccount->createView(),
        ]);
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
}
