<?php

namespace App\Http\Interfaces;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthRepositoryInterface{

    public function login(Request $request): JsonResponse;

    public function addSuggest(Request $request): JsonResponse;

    public function allNotifications(): JsonResponse;

    public function communication():JsonResponse;

    public function getProfile(Request $request): JsonResponse;

    public function paper_sheet_exam(Request $request, $id);

    public function latestPaperExamDelete(): JsonResponse;

    public function paper_sheet_exam_show(): JsonResponse;

    public function updateProfile(Request $request): JsonResponse;

    public function home_page(): JsonResponse;

    public function allClasses(): JsonResponse;

    public function all_exams(): JsonResponse;

    public function startYourJourney(Request $request):JsonResponse;

    public function videosResources(): JsonResponse;

    public function findExamByClassById($id):JsonResponse;

    public function add_device_token(Request $request);

    public function add_notification(Request $request): JsonResponse;

    public function user_add_screenshot(): JsonResponse;

    public function logout(Request $request): JsonResponse;

    public function paperSheetExamForStudentDetails(): JsonResponse;

    public function inviteYourFriends(): JsonResponse;

    public function examCountdown(): JsonResponse;

    public function notificationUpdateStatus($id): JsonResponse;

}
