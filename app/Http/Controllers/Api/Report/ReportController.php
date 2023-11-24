<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller{


    public ReportRepositoryInterface $reportRepositoryInterface;

    public function __construct(ReportRepositoryInterface $reportRepositoryInterface)
    {

        $this->reportRepositoryInterface = $reportRepositoryInterface;
    }

    public function studentAddReport(Request $request):JsonResponse{

        return $this->reportRepositoryInterface->studentAddReport($request);
    }

    public function allByStudent():JsonResponse{

        return $this->reportRepositoryInterface->allByStudent();

    }

    public function delete($id):JsonResponse{

        return $this->reportRepositoryInterface->delete($id);

    }

}
