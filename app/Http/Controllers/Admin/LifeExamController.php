<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestLifeExam;
use App\Http\Requests\StoreLiveExam;
use App\Http\Requests\StoreSubjectClasses;
use App\Models\LifeExam;
use App\Models\Season;
use App\Models\Term;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LifeExamController extends Controller
{
    use PhotoTrait;
    use AdminLogs;


    use FirebaseNotification;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $life_exams = LifeExam::get();
            return Datatables::of($life_exams)
                ->addColumn('action', function ($life_exams) {
                    return '
                            <button type="button" data-id="' . $life_exams->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $life_exams->id . '" data-title="' . $life_exams->name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('term_id', function ($life_exams) {
                    return '<td>' . $life_exams->term->name_ar . '</td>';
                })
                ->editColumn('season_id', function ($life_exams) {
                    return '<td>' . $life_exams->season->name_ar . '</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.life_exams.index');
        }
    }



    public function create()
    {
        $data['terms'] = Term::get();
        $data['seasons'] = Season::get();
        return view('admin.life_exams.parts.create', $data);
    }



    public function store(StoreLiveExam $request): JsonResponse
    {
        $inputs = $request->all();

        if($request->hasFile('answer_video_file')){
            $inputs['answer_video_file'] = $this->saveImage($request->answer_video_file, 'answer_video_file', 'photo');
        }


        if (LifeExam::create($inputs)) {

            $this->adminLog('تم اضافة امتحان لايف');
            $this->sendFirebaseNotification(['title' => 'اشعار جديد', 'body' => $request->name_ar, 'term_id' => $request->term_id], $request->season_id);

            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function edit(LifeExam $lifeExam)
    {
        $data['terms'] = Term::get();
        $data['seasons'] = Season::get();
        return view('admin.life_exams.parts.edit', compact('lifeExam', 'data'));
    }



    public function update(LifeExam $lifeExam,StoreLiveExam $request): JsonResponse
    {
        $inputs = $request->all();


        if($request->hasFile('answer_video_file')){
            $inputs['answer_video_file'] = $this->saveImage($request->answer_video_file, 'answer_video_file', 'photo');
        }

        if ($lifeExam->update($inputs)) {
            $this->adminLog('تم تحديث امتحان لايف');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request): JsonResponse
    {
        $subject_class = lifeExam::where('id', $request->id)->firstOrFail();
        $subject_class->delete();
        $this->adminLog('تم حذف امتحان لايف');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
