<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\AllExamRequest;
use App\Models\AllExam;
use App\Models\Season;
use App\Models\Term;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AllExamController extends Controller
{
    use FirebaseNotification,AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $all_exams = AllExam::get();
            return Datatables::of($all_exams)
                ->addColumn('action', function ($all_exams) {
                    return '
                            <button type="button" data-id="' . $all_exams->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $all_exams->id . '" data-title="' . $all_exams->name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.all_exams.index');
        }
    }



    public function create()
    {
        $data['terms'] = Term::all();
        $data['seasons'] = Season::all();
        return view('admin.all_exams.parts.create', compact('data'));
    }



    public function store(AllExamRequest $request, AllExam $allExam): JsonResponse
    {
        $inputs = $request->all();

        if ($request->has('pdf_file_upload')) {
            $inputs['pdf_file_upload'] = saveFile('all_exams/pdf_file_uploads', $request->pdf_file_upload);
        }

        if ($request->has('answer_pdf_file')) {
            $inputs['answer_pdf_file'] = saveFile('all_exams/pdf_answers', $request->answer_pdf_file);
        }

        if ($request->has('answer_video_file')) {
            $inputs['answer_video_file'] = saveFile('all_exams/videos_answers', $request->answer_video_file);
        }

        if ($request->has('image_result')) {
            $inputs['image_result'] = saveFile('all_exams/image_result', $request->image_result);
        }


        if ($inputs['answer_video_youtube'] != null) {
            $inputs['answer_video_is_youtube'] = 1;
        }else{

            $inputs['answer_video_is_youtube'] = 0;
        }

        $allExamSave = $allExam->create($inputs);

        if($allExamSave->save()) {

            $this->sendFirebaseNotificationWhenAddedExam(['title' => "اشعار جديد","body" => "تم اضافه امتحان شامل جديد علي المنصه"],$allExamSave->season_id,"full_exam",$allExamSave->id);

            $this->adminLog('تم اضافة امتحان شامل');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(AllExam $allExam)
    {
        $data['terms'] = Term::all();
        $data['seasons'] = Season::all();
        return view('admin.all_exams.parts.edit', compact('data', 'allExam'));
    }


    public function update(AllExamRequest $request, AllExam $allExam): JsonResponse
    {
        $inputs = $request->all();

        if ($request->has('pdf_file_upload')) {
            if (file_exists($allExam->pdf_file_upload)) {
                unlink($allExam->pdf_file_upload);
            }
            $inputs['pdf_file_upload'] = saveFile('all_exams/pdf_file_uploads', $request->pdf_file_upload);
        }

        if ($request->has('answer_pdf_file')) {
            if (file_exists($allExam->answer_pdf_file)) {
                unlink($allExam->answer_pdf_file);
            }
            $inputs['answer_pdf_file'] = saveFile('all_exams/pdf_answers', $request->answer_pdf_file);
        }

        if ($request->has('answer_video_file')) {
            if (file_exists($allExam->answer_video_file)) {
                unlink($allExam->answer_video_file);
            }
            $inputs['answer_video_file'] = saveFile('all_exams/videos_answers', $request->answer_video_file);
        }

        if ($request->has('image_result')) {
            if (file_exists($allExam->image_result)) {
                unlink($allExam->image_result);
            }
            $inputs['image_result'] = saveFile('all_exams/image_result', $request->image_result);
        }

        if ($inputs['answer_video_youtube'] != null) {
            $inputs['answer_video_is_youtube'] = 1;
        }else{

            $inputs['answer_video_is_youtube'] = 0;
        }

        if ($allExam->update($inputs)) {
            $this->adminLog('تم تحديث امتحان شامل');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request): JsonResponse
    {
        $onlineExam = AllExam::where('id', $request->id)->firstOrFail();
        $onlineExam->delete();
        $this->adminLog('تم حذف امتحان شامل');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
