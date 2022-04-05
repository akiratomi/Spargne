<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\MeetingRequest;
use App\Entity\User;
use App\Entity\AdvisorSchedule;

use App\Form\BookMeetingType;

class SecretaryController extends AbstractController
{
    #[Route('/secretary/meetingRequests', name: 'meetingRequests')]
    public function meetingRequests(): Response
    {
        $meetingRequests = $this->getDoctrine()->getRepository(MeetingRequest::class)->findAll();

        return $this->render('secretary/meetingRequests.html.twig', [
            'meetingRequests' => $meetingRequests,
        ]);
    }

    #[Route('/secretary/advisorSchedule/{idAccount}/{idMeeting}', name: 'advisorScheduleWithMeetingRequest')]
    public function advisorScheduleWithMeetingRequest(int $idAccount, int $idMeeting, Request $request): Response
    {
        $currentMeetingRequest = $this->getDoctrine()->getRepository(MeetingRequest::class)->find($idMeeting);
        $advisor = $this->getDoctrine()->getRepository(User::class)->find($idAccount);
        $advisorMeetings = $this->getDoctrine()->getRepository(AdvisorSchedule::class)->findBy(array('advisor' => $advisor));

        $advisorMeetingsMonday = array();
        $advisorMeetingsTuesday = array();
        $advisorMeetingsWednesday = array();
        $advisorMeetingsThursday = array();
        $advisorMeetingsFriday = array();
        $advisorMeetingsSaturday = array();
        $advisorMeetingsSunday = array();

        foreach ($advisorMeetings as $key => $advisorMeeting) {
            if($advisorMeeting->getDate()->format('D') == 'Mon'){
                array_push($advisorMeetingsMonday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Tue'){
                array_push($advisorMeetingsTuesday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Wed'){
                array_push($advisorMeetingsWednesday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Thu'){
                array_push($advisorMeetingsThursday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Fri'){
                array_push($advisorMeetingsFriday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Sat'){
                array_push($advisorMeetingsSaturday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Sun'){
                array_push($advisorMeetingsSunday, $advisorMeeting);
            }
        }

        $advisorSchedule = new AdvisorSchedule();

        $desiredDateExplode = explode("-", $currentMeetingRequest->getDesiredDate()->format('Y-m-d-H-i-s'));
        $date = new \DateTime();
        $date->setDate($desiredDateExplode[0], $desiredDateExplode[1], $desiredDateExplode[2]);
        $date->setTime(8, 0);
        $advisorSchedule->setDate($date);

        $duration = new \DateTime();
        $duration->setTime(1, 0);
        $advisorSchedule->setDuration($duration);

        $advisorSchedule->setTopic($currentMeetingRequest->getTopic());
        $advisorSchedule->setCustomer($currentMeetingRequest->getCustomer());
        $advisorSchedule->setAdvisor($currentMeetingRequest->getCustomer()->getAdvisor());

        $formBookMeeting = $this->createForm(BookMeetingType::class, $advisorSchedule);
        if($request->isMethod('POST')){
            $formBookMeeting->handleRequest($request);
            if ($formBookMeeting->isSubmitted()&&$formBookMeeting->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($advisorSchedule);
                $em->flush();

                $em->remove($currentMeetingRequest);
                $em->flush();

                return $this->redirectToRoute('meetingRequests');
            }
        }

        return $this->render('secretary/advisorSchedule.html.twig', [
            'currentMeetingRequest' => $currentMeetingRequest,
            'formBookMeeting'=>$formBookMeeting->createView(),
            'advisor' => $advisor,
            'advisorMeetingsMonday' => $advisorMeetingsMonday,
            'advisorMeetingsTuesday' => $advisorMeetingsTuesday,
            'advisorMeetingsWednesday' => $advisorMeetingsWednesday,
            'advisorMeetingsThursday' => $advisorMeetingsThursday,
            'advisorMeetingsFriday' => $advisorMeetingsFriday,
            'advisorMeetingsSaturday' => $advisorMeetingsSaturday,
            'advisorMeetingsSunday' => $advisorMeetingsSunday,
        ]);
    }

    #[Route('/secretary/advisorSchedule/{idAccount}', name: 'advisorSchedule')]
    public function advisorSchedule(int $idAccount, Request $request): Response
    {
        $advisor = $this->getDoctrine()->getRepository(User::class)->find($idAccount);
        $advisorMeetings = $this->getDoctrine()->getRepository(AdvisorSchedule::class)->findBy(array('advisor' => $advisor));

        $advisorMeetingsMonday = array();
        $advisorMeetingsTuesday = array();
        $advisorMeetingsWednesday = array();
        $advisorMeetingsThursday = array();
        $advisorMeetingsFriday = array();
        $advisorMeetingsSaturday = array();
        $advisorMeetingsSunday = array();

        foreach ($advisorMeetings as $key => $advisorMeeting) {
            if($advisorMeeting->getDate()->format('D') == 'Mon'){
                array_push($advisorMeetingsMonday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Tue'){
                array_push($advisorMeetingsTuesday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Wed'){
                array_push($advisorMeetingsWednesday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Thu'){
                array_push($advisorMeetingsThursday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Fri'){
                array_push($advisorMeetingsFriday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Sat'){
                array_push($advisorMeetingsSaturday, $advisorMeeting);
            }
            if($advisorMeeting->getDate()->format('D') == 'Sun'){
                array_push($advisorMeetingsSunday, $advisorMeeting);
            }
        }

        return $this->render('secretary/advisorSchedule.html.twig', [
            'advisor' => $advisor,
            'advisorMeetingsMonday' => $advisorMeetingsMonday,
            'advisorMeetingsTuesday' => $advisorMeetingsTuesday,
            'advisorMeetingsWednesday' => $advisorMeetingsWednesday,
            'advisorMeetingsThursday' => $advisorMeetingsThursday,
            'advisorMeetingsFriday' => $advisorMeetingsFriday,
            'advisorMeetingsSaturday' => $advisorMeetingsSaturday,
            'advisorMeetingsSunday' => $advisorMeetingsSunday,
        ]);
    }
}
