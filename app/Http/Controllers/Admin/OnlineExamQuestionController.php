<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\AllExam;
use App\Models\LifeExam;
use App\Models\OnlineExam;
use App\Models\OnlineExamQuestion;
use App\Models\Question;
use App\Models\Season;
use App\Models\Term;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class OnlineExamQuestionController extends Controller{

    use AdminLogs;

    public function index(Request $request){

        if ($request->ajax()) {
            $online_exam_questions = OnlineExamQuestion::query()->latest()->get();
            return Datatables::of($online_exam_questions)
                ->addColumn('action', function ($online_exam_questions) {

                        return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $online_exam_questions->id . '" data-title="' . $online_exam_questions->id . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })

                ->addColumn('type', function ($online_exam_questions) {

                    if ($online_exam_questions->online_exam_id != null) {

                        if($online_exam_questions->online_exam->type == 'video'){

                            return '<span class="badge badge-primary-gradient">فيديو</span>';

                        }elseif ($online_exam_questions->online_exam->type == 'lesson'){

                            return '<span class="badge badge-primary-gradient">درس</span>';
                        }else{

                            return '<span class="badge badge-primary-gradient">فصل</span>';
                        }

                    }elseif ($online_exam_questions->all_exam_id != null){

                        return '<span class="badge badge-info">امتحان شامل</span>';

                    } else {
                        return '<span class="badge badge-info">امتحان لايف</span>';
                    }
                })


                ->addColumn('exam_type', function ($online_exam_questions) {

                    if ($online_exam_questions->online_exam_id != null) {

                        return  $online_exam_questions->online_exam->name_ar;

                    }elseif ($online_exam_questions->all_exam_id != null){

                        return $online_exam_questions->all_exam->name_ar;

                    } else {
                        return $online_exam_questions->live_exam->name_ar;
                    }
                })


                ->editColumn('question_id', function ($online_exam_questions) {

                    return  $online_exam_questions->question->question;

                })

                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.online_exam_questions.index');
        }

    }

    public function create()
    {
        $types = [
           "class" => "فصل",
            "lesson" => "درس",
            "video" => "واجب",
            "all_exam" => "امتحان شامل",
           "life_exam" => "امتحان لايف",

        ];

        $seasons = Season::query()->get();
        $terms = Term::query()->get();
        $questions = Question::query()->get();
        return view('admin.online_exam_questions.parts.create', compact('types','seasons','terms','questions'));
    }


    public function getAllExamsByType(): array
    {

        if(in_array(request()->exam_type,['video','lesson','class'])){

            $exam = OnlineExam::query()
                ->where('exam_type','=','online')
                ->where('season_id','=',request()->season_id)
                ->where('term_id','=',request()->term_id)
                ->where('type','=',request()->exam_type)
                ->pluck('name_ar','id')->toArray();


        }elseif (request()->exam_type == 'life_exam'){

            $exam = LifeExam::query()
                ->where('season_id','=',request()->season_id)
                ->where('term_id','=',request()->term_id)
                ->pluck('name_ar','id')->toArray();

        }else{

            $exam = AllExam::query()
                ->where('season_id','=',request()->season_id)
                ->where('term_id','=',request()->term_id)
                ->pluck('name_ar','id')->toArray();
        }


        return $exam;

    }


    public function getAllQuestionsByExamType(): array
    {


        if(request()->exam_type == 'class'){
            return Question::query()
                ->where('season_id','=',request()->season_id)
                ->where('term_id','=',request()->term_id)
                ->where('type','=','subject_class')
                ->pluck('question','id')->toArray();

        }else{

            return Question::query()
                ->where('season_id','=',request()->season_id)
                ->where('term_id','=',request()->term_id)
                ->where('type','=',request()->exam_type)
                ->pluck('question','id')->toArray();
        }

    }

    public function store(Request $request): JsonResponse{

        $request->validate( [
            'season_id' => 'required',
            'term_id' => 'required',
            'exam_type' => 'required',
            'exam' => 'required',
            'questionIds' => 'required',
        ],[
            'season_id.required' => 'يرجي ادخال الصف الدراسي',
            'term_id.required' => 'يرجي ادخال التيرم',
            'exam_type.required' => 'يرجي ادخال نوع الامتحان',
            'exam.required' => 'يرجي اختيار امتحان معين',
            'questionIds.required' => 'يرجي اختيار اي سؤال لهذا الامتحان',
        ]);

        if(in_array(request()->exam_type,['video','lesson','class'])){

        $online_exam = OnlineExam::query()
            ->where('id','=',request()->exam)
            ->where('type','=',request()->exam_type)
            ->first();


        $online_exam->questions()->sync(request()->questionIds);


        if ($online_exam->save()) {
            $this->adminLog('تم اضافه اسئله لامتحان اونلاين');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }

        }elseif (request()->exam_type == 'life_exam'){

            $live_exam = LifeExam::query()
                ->where('id','=',request()->exam)
                ->first();


            $live_exam->questions()->sync(request()->questionIds);

            if ($live_exam->save()) {
                $this->adminLog('تم اضافه اسئله لامتحان لايف');
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 405]);
            }

        }else{

            $all_exam = AllExam::query()
                ->where('id','=',request()->exam)
                ->first();


            $all_exam->questions()->sync(request()->questionIds);

            if ($all_exam->save()) {
                $this->adminLog('تم اضافه اسئله لامتحان شامل');
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 405]);
            }
        }


    }

    public function edit(Admin $admin)
    {

    }


    public function update(Request $request, $id)
    {

    }


    public function delete(Request $request)
    {
        $online_exam_question = OnlineExamQuestion::query()
            ->where('id', $request->id)
            ->first();

            $online_exam_question->delete();
            $this->adminLog('تم حذف سؤال من امتحان');
            return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);

    }

}
