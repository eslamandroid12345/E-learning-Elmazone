<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaperSheetRequest;
use App\Models\OnlineExam;
use App\Models\PapelSheetExam;
use App\Models\PapelSheetExamDegree;
use App\Models\PapelSheetExamTime;
use App\Models\PapelSheetExamUser;
use App\Models\Season;
use App\Models\Term;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PapelSheetExamController extends Controller
{

    use FirebaseNotification , AdminLogs;
    public function index(request $request)
    {
        if ($request->ajax()) {
            $sheet_exams = PapelSheetExam::get();
            return Datatables::of($sheet_exams)
                ->addColumn('action', function ($sheet_exams) {
                    return '
                            <button type="button" data-id="' . $sheet_exams->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $sheet_exams->id . '" data-title="' . $sheet_exams->name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                            <a class="btn btn-pill btn-warning-light questionBtn" data-id="' . $sheet_exams->id . '" data-target="#question_modal" href="' . route('usersExamPapel', $sheet_exams->id) . '"><i class="fa fa-user"></i></a>
                       ';
                })
                ->editColumn('season_id', function ($sheet_exams) {
                    return '<td>'. $sheet_exams->season->name_ar .'</td>';
                })
                ->editColumn('term_id', function ($sheet_exams) {
                    return '<td>'. $sheet_exams->term->name_ar .'</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.papel_sheet_exams.index');
        }
    }



    public function usersExamPapel(Request $request)
    {
        $papelExams = PapelSheetExam::find($request->id);

        $papel_exam_users = PapelSheetExamUser::query()
        ->where('papel_sheet_exam_id', $papelExams->id)
            ->select('user_id')->groupBy('user_id')
            ->get();

        $answers = PapelSheetExamUser::query()
        ->where('papel_sheet_exam_id', $papelExams->id)
            ->whereIn('user_id', $papel_exam_users->pluck('user_id'))
            ->get();
        return view('admin.papel_sheet_exams.parts.text_exam_users', compact('papelExams', 'papel_exam_users'));
    }



    public function paperExamSheet(Request $request)
    {
        $user = PapelSheetExamUser::query()
        ->where('user_id', $request->id)->select('user_id')
            ->groupBy('user_id')
            ->get();

        $exam = PapelSheetExamUser::query()
        ->where('user_id', $request->id)
            ->first('papel_sheet_exam_id');

        $answers = PapelSheetExamUser::query()
        ->where('papel_sheet_exam_id', $exam->papel_sheet_exam_id)
            ->where('user_id', $user->pluck('user_id'))
            ->get();

        return view('admin.papel_sheet_exams.parts.exam_paper_sheets', compact('answers'));
    }


    public function paperExamSheetStore(Request $request): JsonResponse
    {
        $examSheetId = PapelSheetExamDegree::findOrFail($request->papel_sheet_exam_id);
        $examSheetId->update(['degree' => $request->degree]);
        return response()->json(['status' => 200, 'message' => 'تم اضافه الدرجه بنجاح']);
    }


    public function create()
    {
        $seasons = Season::all();
        $terms = Term::all();
        return view('admin.papel_sheet_exams.parts.create',compact('seasons','terms'));
    }


    public function store(StorePaperSheetRequest $request): JsonResponse
    {
        $inputs = $request->all();

        $papelSheetExam = PapelSheetExam::create($inputs);
        if ($papelSheetExam->save()) {

            for ($t = 0; $t < count($request->times['from']); $t++) {
                PapelSheetExamTime::create([
                    'from' => $request->times['from'][$t],
                    'to' => $request->times['to'][$t],
                    'papel_sheet_exam_id' =>  $papelSheetExam->id,
                ]);
            }

            $this->adminLog('تم اضافة امتحان ورقي');
            $this->sendFirebaseNotification(['title' => 'اشعار جديد', 'body' => $request->name_ar, 'term_id' => $request->term_id],$request->season_id);
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(PapelSheetExam $papelSheetExam)
    {
        $times = PapelSheetExamTime::where('papel_sheet_exam_id',$papelSheetExam->id)->get();
        $seasons = Season::all();
        $terms = Term::all();
        return view('admin.papel_sheet_exams.parts.edit', compact('seasons','terms', 'papelSheetExam','times'));
    }


    public function update(Request $request, PapelSheetExam $papelSheetExam): JsonResponse
    {

        if ($papelSheetExam->update($request->all())) {
            PapelSheetExamTime::where('papel_sheet_exam_id',$papelSheetExam->id)->delete();
            for ($t = 0; $t < count($request->times['from']); $t++) {
                PapelSheetExamTime::create([
                    'from' => $request->times['from'][$t],
                    'to' => $request->times['to'][$t],
                    'papel_sheet_exam_id' =>  $papelSheetExam->id,
                ]);
            }
            $this->adminLog('تم تحديث امتحان ورقي');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request): JsonResponse
    {
        $papelSheetExam = PapelSheetExam::query()
        ->where('id', $request->id)
            ->firstOrFail();

        $papelSheetExam->delete();
        $this->adminLog('تم حذف امتحان ورقي');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
