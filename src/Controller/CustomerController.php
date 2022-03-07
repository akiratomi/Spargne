<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Account;
use App\Entity\Transferts;
use App\Entity\TransfertsType;
use App\Entity\Beneficiary;


class CustomerController extends AbstractController
{
    #[Route('/customer/homePage', name: 'customerHomePage')]
    public function homePage(): Response
    {
        return $this->render('customer/homePage.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('/customer/accounts', name: 'customerAccounts')]
    public function accounts(): Response
    {
        return $this->render('customer/accounts.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('/customer/accounts/transactions/{id}', name: 'customerAccountTransaction')]
    public function transactions(int $id): Response
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        $transferts = $this->getDoctrine()->getRepository(Transferts::class)->getAllTransactionByYear("2022",$account);

        return $this->render('customer/transactions.html.twig', [
            'controller_name' => 'CustomerController',
            'account' => $account,
            'transferts' => $transferts,
        ]);
    }

    

    #[Route('/customer/transferts', name: 'customerTransferts')]
    public function transferts(): Response
    {
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
                    dump($accountTo);
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
                        if($accountTo->getLimitBalance() == null){
                            $canTransact = true;
                        }else{
                            if( ($accountTo->getBalance() + $amount) < $accountTo->getLimitBalance()){
                                $canTransact = true;
                            }
                        }

                        if($canTransact){
                            $accountFrom->setBalance( $accountFrom->getBalance() - $amount );
                            $accountTo->setBalance( $accountTo->getBalance() + $amount );

                            $transfertType = $this->getDoctrine()->getRepository(TransfertsType::class)->findOneBy(array('name'=>'Advisor transfert'));
                            $transfert = new Transferts();
                            $transfert->setType($transfertType);
                            $transfert->setFromAccount($accountFrom);
                            $transfert->setDestinationAccount($accountTo);
                            $date = new \DateTime(date("m.d.y"));
                            $transfert->setDate($date);
                            $transfert->setAmount($amount);

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($accountFrom);
                            $em->persist($accountTo);
                            $em->persist($transfert);
                            $em->flush();

                            return $this->redirectToRoute('customerAccounts');
                        }else{
                            return $this->render('customer/transferts.html.twig', [
                                'errorTransact' => 'Error : The "destination" selected account limit balance would be overated by this transfert',
                            ]);
                        }
                    }else{
                        return $this->render('customer/transferts.html.twig', [
                            'errorTransact' => 'Error : The "from" selected account hasn\'t enough balance to complet transfert',
                        ]);
                    }
                }else{
                    return $this->render('customer/transferts.html.twig', [
                        'errorTransact' => 'Error : An error occured please try later',
                    ]);
                }
            }else{
                return $this->render('customer/transferts.html.twig', [
                    'errorTransact' => 'Error : Please select all field to complet transfert',
                ]);
            }
        }

        return $this->render('customer/transferts.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('/customer/cards', name: 'customerCards')]
    public function cards(): Response
    {
        return $this->render('customer/cards.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('/customer/beneficiariesManagement', name: 'beneficiariesManagement')]
    public function beneficiariesManagement(): Response
    {
        if(isset($_POST['beneficiaryAddBtn'])){
            $newBeneficiary = new Beneficiary();
            $test_acc = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('iban'=>$_POST['iban']));
            if($test_acc != null){
                $date = new \DateTime(date("m.d.y"));
                $newBeneficiary->setAddedDate($date);
                $newBeneficiary->setOwner($this->getUser());
                $newBeneficiary->setAccount($test_acc);
                $test_name = $this->getDoctrine()->getRepository(Beneficiary::class)->findOneBy(array('name'=>$_POST['name'], 'owner'=>$this->getUser()));
                if($test_name == null){
                    $newBeneficiary->setName($_POST['name']);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newBeneficiary);
                    $em->flush();

                    return $this->redirectToRoute('beneficiariesManagement');
                }else{
                    return $this->render('customer/beneficiariesManagement.html.twig', [
                        'error' => 'That beneficiary name albready exist',
                    ]);
                }
                

            }else{
                return $this->render('customer/beneficiariesManagement.html.twig', [
                    'error' => 'No account found for this iban',
                ]);
            }
        }

        return $this->render('customer/beneficiariesManagement.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    
}
