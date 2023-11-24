<?php

namespace App\Http\Controllers\Api\DegreeOfExamsDetails;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\DegreeExamsDetailsRepositoryInterface;
use Illuminate\Http\JsonResponse;

class DegreeExamsDetailsController extends Controller{


    public DegreeExamsDetailsRepositoryInterface $degreeExamsDetailsRepositoryInterface;

    public function __construct(DegreeExamsDetailsRepositoryInterface $degreeExamsDetailsRepositoryInterface){

        $this->degreeExamsDetailsRepositoryInterface = $degreeExamsDetailsRepositoryInterface;

    }
    
    public function allExamsDegreeDetails(): JsonResponse
    {

       return $this->degreeExamsDetailsRepositoryInterface->allExamsDegreeDetails();
    }


    public function videosByLessonDegreeDetails($id): JsonResponse
    {

        return $this->degreeExamsDetailsRepositoryInterface->videosByLessonDegreeDetails($id);

    }

    public function lessonDegreeDetails($id): JsonResponse
    {

        return $this->degreeExamsDetailsRepositoryInterface->lessonDegreeDetails($id);

    }

    public function classDegreeDetails($id): JsonResponse{

        return $this->degreeExamsDetailsRepositoryInterface->classDegreeDetails($id);
    }
}
