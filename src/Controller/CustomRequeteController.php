<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Account;
use App\Entity\Transferts;

class CustomRequeteController extends AbstractController
{
    #[Route('/api/accounts/{uuid}', name: 'GetAccountByUuid')]
    public function GetAccountByUuid(int $uuid): Response|Account
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getDoctrine()->getRepository(User::class)->findBy(array('uuid'=> $uuid));
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findBy(array('owner'=> $user));
        
        $arr = array();

        foreach ($accounts as $key => $value) {
            $type = (object) array(
                'id' => $value->getType()->getId(),
                'name' => $value->getType()->getName(),
            );

            $obj = (object) array(
                'id' => $value->getId(),
                'num' => $value->getNum(),
                'iban' => $value->getIban(),
                'balance' => $value->getBalance(),
                'creation_date' => $value->getCreationDate()->format('Y-m-d H:i:s'),
                'limiteBalance' => $value->getLimitBalance(),
                'overdraft' => $value->getOverdraft(),
                'rate' => $value->getRate(),
                'type' => $type,
                'name' => $value->getName(),
            );

            array_push($arr, $obj);
        }
        $arrEncoded = json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $retour = new Response($arrEncoded,
        Response::HTTP_CREATED,
        [
            'content-type'=> 'application/json',
            'charset' => "utf-8",
        ]);
        return $retour;
    }

    #[Route('/api/transfert/{id}/{first}/{last}', name: 'GetTransfertsByAccount')]
    public function GetTransfertsByAccount(int $id,int $first,int $last): Response|Account
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        $transferts = $this->getDoctrine()->getRepository(Transferts::class)->getAllTransactionByYear(2022,$account);
        
        $arr = array();

        for($i = $first ; $i < $last ; $i++){
            if(isset($transferts[$i])){
                $obj = (object) array(
                    'id' => $transferts[$i]->getId(),
                    'date' => $transferts[$i]->getDate()->format('Y-m-d H:i:s'),
                    'fromAccount' => $transferts[$i]->getFromAccount()->getId(),
                    'destinationAccount' => $transferts[$i]->getDestinationAccount()->getId(),
                    'fromName' => $transferts[$i]->getFromAccount()->getOwner()->getName(),
                    'destinationName' => $transferts[$i]->getDestinationAccount()->getOwner()->getName(),
                    'fromFirstName' => $transferts[$i]->getFromAccount()->getOwner()->getFirstName(),
                    'destinationFirstName' => $transferts[$i]->getDestinationAccount()->getOwner()->getFirstName(),
                    'amount' => $transferts[$i]->getAmount(),
                    'type' => $transferts[$i]->getType()->getName(),
                );
    
                array_push($arr, $obj);
            }
        }

        $arrEncoded = json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $retour = new Response($arrEncoded,
        Response::HTTP_CREATED,
        [
            'content-type'=> 'application/json',
            'charset' => "utf-8",
        ]);
        return $retour;
    }
}
