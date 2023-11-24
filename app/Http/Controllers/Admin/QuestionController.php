<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use App\Models\Answer;
use App\Models\Lesson;
use App\Models\Season;
use App\Models\AllExam;
use App\Models\LifeExam;
use App\Models\Question;
use App\Traits\AdminLogs;
use App\Models\VideoParts;
use App\Traits\PhotoTrait;
use App\Models\SubjectClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exports\QuestionExport;
use App\Imports\QuestionImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\QuestionUpdateRequest;

class QuestionController extends Controller
{
    use PhotoTrait, AdminLogs;


    public function index(request $request)
    {
        if ($request->ajax()) {
            $questions = Question::select('*');
            return Datatables::of($questions)
                ->addColumn('action', function ($questions) {
                    return '
                            <button type="button" data-id="' . $questions->id . '" class="btn btn-pill btn-info-light editBtn">تعديل</button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $questions->id . '" data-title="' . strip_tags(str_replace('</p>', '', $questions->question)) . '">
                                   حذف
                            </button>
                            <button type="button" ' . ($questions->question_type == 'text' ? 'hidden' : '') . ' data-id="' . $questions->id . '" class="btn btn-pill btn-success-light editBtnAnswer">الاجابة</button>
                       ';
                })
                ->editColumn('type', function ($questions) {
                    if ($questions->type == 'video')
                        return 'واجب';
                    else if ($questions->type == 'lesson')
                        return 'درس';
                    else if ($questions->type == 'all_exam')
                        return 'امتحان شامل';
                    else if ($questions->type == 'subject_class')
                        return 'فصل';
                    else if ($questions->type == 'life_exam')
                        return 'امتحان لايف';
                })
                ->editColumn('question', function ($questions) {
                    return \Str::limit($questions->question, 50);
                })
                ->editColumn('season_id', function ($questions) {
                    return $questions->season->name_ar;
                })
                ->editColumn('term_id', function ($questions) {
                    return $questions->term->name_ar;
                })
                ->editColumn('difficulty', function ($questions) {
                    if ($questions->difficulty == 'low')
                        return '<span class="badge badge-success">سـهل</span>';
                    else if ($questions->difficulty == 'mid')
                        return '<span class="badge badge-info">متوسـط</span>';
                    else
                        return '<span class="badge badge-danger">صـعب</span>';
                })
                ->filter(function ($questions) use ($request) {
                    if ($request->get('type')) {
                        $questions->where('season_id', $request->get('type'))->get();
                    }
                })
                ->rawColumns([])
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.questions.index');
        }
    }




    public function create()
    {
        $seasons = Season::get();
        $terms = Term::get();
        return view('admin.questions.parts.create', compact('seasons', 'terms'));
    }


    public function store(QuestionStoreRequest $request): JsonResponse
    {
        $inputs = $request->all();

        if ($backgroundImage = $request->file('image')) {

            $destinationPath = 'assets/uploads/questions';
            $imageLink = date('YmdHis') . time().rand(1,200000)."." . $backgroundImage->getClientOriginalExtension();
            $backgroundImage->move($destinationPath, $imageLink);
            $inputs['image'] = "assets/uploads/questions/".$imageLink;

        }

        $question = Question::create($inputs);

        if ($question->save()) {
            $this->adminLog('تم اضافة سؤال جديد');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }

    }

    public function answer($id)
    {
        $question = Question::findOrFail($id);
        $answers = Answer::query()->select('id', 'answer', 'answer_status')->where('question_id', $id)->get();
        return view('admin.questions.parts.answers', compact('question', 'answers'));
    }




    public function addAnswer(Request $request)
{
    DB::beginTransaction();
        $questionId = $request->question_id;
        $answers = $request->answer;
        $answerStatus = $request->answer_status;
        $existingAnswers = Answer::where('question_id', $questionId)->get();
        if ($existingAnswers->count() > 0) {
            foreach($existingAnswers as $answer){
                foreach ($answers as $key => $value) {
                    if ($answer->answer_number == $key) {
                        $answer->update([
                            'answer' => $value,
                            'answer_status' => ($answerStatus == $key) ? 'correct' : 'un_correct',
                        ]);
                    }
                }
            }
            } else {
                foreach ($answers as $key => $value) {
                    Answer::create([
                        'answer' => $value,
                        'question_id' => $questionId,
                        'answer_status' => ($answerStatus == $key) ? 'correct' : 'un_correct',
                        'answer_number' => $key,
                    ]);
                }
            }
        DB::commit();
        return response()->json(['status' => 200]);
}


    public function edit(Question $question)

    {
        $seasons = Season::get();
        $terms = Term::get();
        return view('admin.questions.parts.edit', compact('question', 'seasons', 'terms'));
    }


    public function update(QuestionUpdateRequest $request, Question $question): JsonResponse
    {

        $inputs = $request->all();

        if ($backgroundImage = $request->file('image')) {

            $destinationPath = 'assets/uploads/questions';
            $imageLink = date('YmdHis') . time().rand(1,200000)."." . $backgroundImage->getClientOriginalExtension();
            $backgroundImage->move($destinationPath, $imageLink);

            $inputs['image'] = "assets/uploads/questions/".$imageLink;

        }else{

            $inputs['image'] = $question->image;
        }


        if ($question->update($inputs)) {
            $this->adminLog('تم تحديث سؤال ');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request)
    {
        $questions = Question::where('id', $request->id)->firstOrFail();
        $questions->delete();
        $this->adminLog('تم حذف سؤال');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function questionExport(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new QuestionExport, 'question.xlsx');
    }

    public function questionImport(Request $request): \Illuminate\Http\JsonResponse
    {
        $import = Excel::import(new QuestionImport, $request->exelFile);
        if ($import) {
            $this->adminLog('تم استيراد سؤال');
            return response()->json(['status' => 200]);
        } else
            return response()->json(['status' => 500]);
    }

}
