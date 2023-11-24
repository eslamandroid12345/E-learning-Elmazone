<?php

namespace App\Http\Interfaces;

use App\Http\Requests\ReportApiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ReportRepositoryInterface{


    public function studentAddReport(Request $request):JsonResponse;
    public function allByStudent():JsonResponse;

    public function delete($id):JsonResponse;

}