<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public AuthRepositoryInterface $authRepositoryInterface;

    public function __construct(AuthRepositoryInterface $authRepositoryInterface)
    {

        $this->authRepositoryInterface = $authRepositoryInterface;
    }

    public function login(Request $request): JsonResponse
    {
        return $this->authRepositoryInterface->login($request);
    }

    public function addSuggest(Request $request): JsonResponse
    {
        return $this->authRepositoryInterface->addSuggest($request);
    }


    public function allNotifications(): JsonResponse
    {

        return $this->authRepositoryInterface->allNotifications();
    }

    public function communication(): JsonResponse
    {
        return $this->authRepositoryInterface->communication();
    }

    public function getProfile(Request $request): JsonResponse
    {

        return $this->authRepositoryInterface->getProfile($request);
    }


    public function paper_sheet_exam(Request $request, $id)
    {

        return $this->authRepositoryInterface->paper_sheet_exam($request, $id);
    }

    public function latestPaperExamDelete(): JsonResponse
    {

        return $this->authRepositoryInterface->latestPaperExamDelete();

    }

    public function paper_sheet_exam_show(): JsonResponse
    {

        return $this->authRepositoryInterface->paper_sheet_exam_show();
    }


    public function updateProfile(Request $request): JsonResponse
    {
        return $this->authRepositoryInterface->updateProfile($request);
    }

    public function home_page(): JsonResponse
    {
        return $this->authRepositoryInterface->home_page();
    }

    public function allClasses(): JsonResponse
    {
        return $this->authRepositoryInterface->allClasses();
    }

    public function all_exams(): JsonResponse
    {
        return $this->authRepositoryInterface->all_exams();
    }

    public function findExamByClassById($id): JsonResponse
    {
        return $this->authRepositoryInterface->findExamByClassById($id);
    }

    public function startYourJourney(Request $request): JsonResponse
    {
        return $this->authRepositoryInterface->startYourJourney($request);
    }

    public function videosResources(): JsonResponse
    {
        return $this->authRepositoryInterface->videosResources();
    }

    public function add_device_token(Request $request)
    {
        return $this->authRepositoryInterface->add_device_token($request);
    }

    public function add_notification(Request $request): JsonResponse
    {
        return $this->authRepositoryInterface->add_notification($request);
    }

    public function user_add_screenshot(): JsonResponse
    {
        return $this->authRepositoryInterface->user_add_screenshot();
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->authRepositoryInterface->logout($request);
    }

    public function paperSheetExamForStudentDetails(): JsonResponse
    {
        return $this->authRepositoryInterface->paperSheetExamForStudentDetails();
    }

    public function inviteYourFriends(): JsonResponse
    {
        return $this->authRepositoryInterface->inviteYourFriends();
    }

    public function examCountdown(): JsonResponse
    {
        return $this->authRepositoryInterface->examCountdown();
    }

    public function notificationUpdateStatus($id): JsonResponse
    {
        return $this->authRepositoryInterface->notificationUpdateStatus($id);
    }



}//end of class Auth
