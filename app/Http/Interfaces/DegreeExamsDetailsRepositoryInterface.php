<?php

namespace App\Http\Interfaces;
use Illuminate\Http\JsonResponse;


interface DegreeExamsDetailsRepositoryInterface{

    public function allExamsDegreeDetails(): JsonResponse;
    public function classDegreeDetails($id): JsonResponse;
    public function videosByLessonDegreeDetails($id): JsonResponse;
    public function lessonDegreeDetails($id): JsonResponse;

}
