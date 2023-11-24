<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineExamRequest;
use App\Models\ExamDegreeDepends;
use App\Models\Lesson;
use App\Models\OnlineExam;
use App\Models\OnlineExamUser;
use App\Models\SubjectClass;
use App\Models\TextExamUser;
use App\Models\VideoParts;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Season;
use App\Models\Term;
use App\Models\OnlineExamQuestion;
use App\Models\Question;
use App\Models\User;


class OnlineExamController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {

        if ($request->ajax()) {
            $online_exams = OnlineExam::latest()->get();
            return DataTables::of($online_exams)
                ->addColumn('action', function ($online_exams) {
                    return '
                            <button type="button" data-id="' . $online_exams->id . '" class="btn btn-pill btn-info-light editBtn">تعديل</button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $online_exams->id . '" data-title="' . $online_exams->name_ar . '">
                                  حذف
                            </button>
                            <a class="btn btn-pill btn-success-light questionBtn" data-id="' . $online_exams->id . '" data-target="#question_modal" href="' . route('indexQuestion', $online_exams->id) . '">اسئله الامتحان</a>
                            <a class="btn btn-pill btn-warning-light questionBtn" data-id="' . $online_exams->id . '" data-target="#question_modal" href="' . route('usersExam', $online_exams->id) . '">طلبه هذا الامتحان</a>
                       ';
                })
                ->editColumn('season_id', function ($online_exams) {
                    return '<td>' . $online_exams->season->name_ar . '</td>';
                })
                ->editColumn('term_id', function ($online_exams) {
                    return '<td>' . $online_exams->term->name_ar . '</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.online_exam.index');
        }
    }


    public function indexQuestion(request $request, $id)
    {

        if ($request->ajax()) {
            $online_exams_questions = OnlineExamQuestion::query()
            ->where('online_exam_id','=',$id)
                ->get();


            return DataTables::of($online_exams_questions)

                ->editColumn('question_id', function ($online_exams_questions) {
                    return   strip_tags(str_replace('</p>', '', $online_exams_questions->question->question));
                })
                ->addColumn('degree', function ($online_exams_questions) {
                    return   $online_exams_questions->question->degree;
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.online_exam.parts.questions', compact('id'));
        }
    }


    public function create()
    {
        $seasons = Season::all();
        $terms = Term::all();
        return view('admin.online_exam.parts.create', compact('seasons', 'terms'));
    }



    public function usersExam(Request $request)
    {
        $exam = OnlineExam::find($request->id);
        $users = User::whereHas('online_exams', function ($online_exam) use ($exam) {

            $online_exam->where('online_exam_id', '=', $exam->id)->groupBy('user_id');
        })->get();


        return view('admin.online_exam.parts.text_exam_users', compact('exam', 'users'));
    }



    public function paperExam($user_id, $exam_id)
    {

        $exam_user = OnlineExam::findOrFail($exam_id);
        $user_exam = User::where('id', '=', $user_id)->first();
        $text_exam_users = TextExamUser::with(['question', 'user'])->where('online_exam_id', $exam_id)
            ->where('user_id', $user_id)
            ->get();

        $online_exam_users = OnlineExamUser::with(['question', 'user', 'answer'])->where('online_exam_id', $exam_id)
            ->where('user_id', $user_id)->get();

        $online_exam_count_text_questions = $exam_user->questions()->where('question_type', '=', 'text')->count();
        $text_exam_users_completed = TextExamUser::with(['question', 'user'])->where('online_exam_id', $exam_id)
            ->where('user_id', $user_id)->where('degree_status', '=', 'completed')->count();

        $exam_depends_for_user = ExamDegreeDepends::where('online_exam_id', $exam_id)->where('user_id', '=', $user_id)
            ->where('exam_depends', '=', 'yes')->first();


        return view(
            'admin.online_exam.parts.exam_paper',
            compact(
                'user_exam',
                'text_exam_users',
                'online_exam_users',
                'exam_user',
                'online_exam_count_text_questions',
                'text_exam_users_completed',
                'exam_depends_for_user'
            )
        );
    }


    public function exam_depends($user_id, $exam_id): JsonResponse
    {

        $text_exam_user_sum_degree = TextExamUser::with(['question', 'user'])->where('online_exam_id', $exam_id)
            ->where('user_id', $user_id)
            ->sum('degree');

        $exam_degree_depends = ExamDegreeDepends::where('online_exam_id', $exam_id)->where('user_id', '=', $user_id)
            ->orderBy('full_degree', 'DESC')->latest()->first();

        $exam_degree_depends->update(['full_degree' => $exam_degree_depends->full_degree += $text_exam_user_sum_degree, 'exam_depends' => 'yes']);
        return response()->json(['status' => 200, 'message' => 'تم اعتماد درجه الامتحان للطالب بنجاح']);
    }


    public function selectTerm(Request $request): array
    {
        return Term::query()->where('season_id', $request->season_id)
            ->pluck('name_ar', 'id')
            ->toArray();

    }


    public function deleteQuestion(Request $request)
    {
        $questions = OnlineExamQuestion::query()
        ->where(['question_id' => $request->question_id, 'online_exam_id' => $request->online_exam_id]);
        $questions->delete();
    }



    public function examble_type(Request $request)
    {
        if ($request->ajax()) {
            if ($request->type == 'lesson') {

                $subjectClass = SubjectClass::where('season_id', $request->season)
                    ->where('term_id', $request->term)
                    ->pluck('id', 'id')->toArray();

                if ($subjectClass) {
                    $data = Lesson::whereIn('subject_class_id', $subjectClass)
                        ->pluck('name_ar', 'id')->toArray();
                }
            } else if ($request->type == 'class') {

                $data = SubjectClass::where('season_id', $request->season)
                    ->where('term_id', $request->term)
                    ->pluck('name_ar', 'id')->toArray();
            } else if ($request->type == 'video') {
                $data = videoParts::pluck('name_ar', 'id')->toArray();
            }
            if (!$data) {
                return response()->json(['' => 'لايوجد بيانات']);
            } else {
                return $data;
            }
        }
    }


    public function store(OnlineExamRequest $request,OnlineExam $online_exam): JsonResponse
    {

        $inputs = $request->all();

        if ($request->has('pdf_file_upload')) {
            $inputs['pdf_file_upload'] = saveFile('online_exams/pdf_file_uploads', $request->pdf_file_upload);
        }

        if ($request->has('answer_pdf_file')) {
            $inputs['answer_pdf_file'] = saveFile('online_exams/pdf_answers', $request->answer_pdf_file);
        }

        if ($request->has('answer_video_file')) {
            $inputs['answer_video_file'] = saveFile('online_exams/videos_answers', $request->answer_video_file);
        }

        if ($request->has('image_result')) {
            $inputs['image_result'] = saveFile('assets/uploads/online_exams/image_result', $request->image_result);
        }

        if ($request->examable_type == 'lesson') {
            $inputs['type'] = 'lesson';
            $inputs['lesson_id'] = $request->examable_id;
        } elseif ($request->examable_type == 'class') {
            $inputs['type'] = 'class';
            $inputs['class_id'] = $request->examable_id;
        } elseif ($request->examable_type == 'video') {
            $inputs['type'] = 'video';
            $inputs['video_id'] = $request->examable_id;
        }

        if ($inputs['answer_video_youtube'] != null) {
            $inputs['answer_video_is_youtube'] = 1;
        }else{

            $inputs['answer_video_is_youtube'] = 0;
        }

        if ($online_exam->create($inputs)) {
            $this->adminLog('تم اضافة امتحان اونلاين');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit(OnlineExam $onlineExam)
    {
        $seasons = Season::all();
        $terms = Term::all();
        return view('admin.online_exam.parts.edit', compact('onlineExam', 'seasons', 'terms'));
    }


    public function update(OnlineExamRequest $request, OnlineExam $onlineExam): JsonResponse
    {

        $inputs = $request->all();


        if ($request->has('pdf_file_upload')) {
            if (file_exists($onlineExam->pdf_file_upload)) {
                unlink($onlineExam->pdf_file_upload);
            }
            $inputs['pdf_file_upload'] = saveFile('online_exams/pdf_file_uploads', $request->pdf_file_upload);
        }

        if ($request->has('answer_pdf_file')) {
            if (file_exists($onlineExam->answer_pdf_file)) {
                unlink($onlineExam->answer_pdf_file);
            }
            $inputs['answer_pdf_file'] = saveFile('online_exams/pdf_answers', $request->answer_pdf_file);
        }

        if ($request->has('answer_video_file')) {
            if (file_exists($onlineExam->answer_pdf_file)) {
                unlink($onlineExam->answer_video_file);
            }
            $inputs['answer_video_file'] = saveFile('online_exams/videos_answers', $request->answer_video_file);
        } // end save file

        if ($request->has('image_result')) {
            if (file_exists($onlineExam->image_result)) {
                unlink($onlineExam->image_result);
            }
            $inputs['image_result'] = saveFile('assets/uploads/online_exams/image_result', $request->image_result);
        } // end save file

        if ($request->examable_type == 'lesson') {
            $inputs['type'] = 'lesson';
            $inputs['lesson_id'] = $request->examable_id;
        } elseif ($request->examable_type == 'class') {
            $inputs['type'] = 'class';
            $inputs['class_id'] = $request->examable_id;
        } elseif ($request->examable_type == 'video') {
            $inputs['type'] = 'video';
            $inputs['video_id'] = $request->examable_id;
        } // end if

        if ($inputs['answer_video_youtube'] != null) {
            $inputs['answer_video_is_youtube'] = 1;
        }else{

            $inputs['answer_video_is_youtube'] = 0;
        }

        if ($onlineExam->update($inputs)) {
            $this->adminLog('تم تحديث امتحان اونلاين');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $onlineExam = OnlineExam::where('id', $request->id)->firstOrFail();
        $onlineExam->delete();
        $this->adminLog('تم حذف امتحان اونلاين');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function addDegreeForTextExam(Request $request): JsonResponse
    {

        $text_exam_user = TextExamUser::findOrFail($request->exam_id);
        $text_exam_user->update(['degree' => $request->degree ?? 0, 'degree_status' => 'completed']);
        return response()->json(['status' => 200, 'message' => 'تم اضافه الدرجه بنجاح']);
    }
}
