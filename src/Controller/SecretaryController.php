<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\MeetingRequest;
use App\Entity\User;
use App\Entity\AdvisorSchedule;

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

    #[Route('/secretary/advisorSchedule/{idAccount}/{idMeeting}', name: 'advisorSchedule')]
    public function advisorSchedule(int $idAccount, int $idMeeting): Response
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

        return $this->render('secretary/advisorSchedule.html.twig', [
            'currentMeetingRequest' => $currentMeetingRequest,
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
