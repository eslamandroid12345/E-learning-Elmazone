<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLesson;
use App\Models\Lesson;
use App\Models\SubjectClass;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Season;

class LessonController extends Controller
{
    use AdminLogs;

    public function index(Request $request)
    {

        $lessonList = Lesson::select('*');

        $subjectClass = SubjectClass::all();
        $seasons = Season::all();
        if ($request->ajax()) {
            if ($request->has('subject_class_id') && $request->subject_class_id != '') {
                $classs = $request->get('subject_class_id');
                $lessonList->where('subject_class_id', $classs);
            }

            $lessons = $lessonList->get();
            return Datatables::of($lessons)
                ->addColumn('action', function ($lessons) {
                    return '
                            <button type="button" data-id="' . $lessons->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $lessons->id . '" data-title="' . $lessons->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('subject_class_id', function ($lessons) {
                    return $lessons->subject_class->name_ar;
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('admin.lessons.index', compact('subjectClass', 'seasons'));
    }


    public function seasonSort(Request $request): string
    {
        $season = $request->id;
        $subjects = SubjectClass::where('season_id', $season)->get();

        $output = '<option value="">اختر الوحدة</option>';

        foreach ($subjects as $subject) {
            $output .= '<option value="' . $subject->id . '">' . $subject->name_ar . ' </option>';
        }
        if ($subjects->count() > 0) {
            return $output;
        } else {
            return '<option value="">لا يوجد وحدات</option>';
        }

    }



    public function create()
    {
        $seasons = Season::get();
        $subjects_classes = SubjectClass::get();
        return view('admin.lessons.parts.create', compact('subjects_classes', 'seasons'));
    }


    public function showUnit(Request $request){

        if ($request->ajax()) {
            $output = '<option value="" style="text-align: center">اختار</option>';
            if ($request->id == 1) {
                $first_levels = SubjectClass::query()->where('season_id', $request->id)->get();
                foreach ($first_levels as $first_level) {
                    if ($first_level->term->status == 'active') {
                        $output .= '<option value="' . $first_level->id . '" style="text-align: center">' . $first_level->name_ar . '</option>';
                    }
                }
            } else if ($request->id == 2) {
                $second_levels = SubjectClass::query()->where('season_id', $request->id)->get();
                foreach ($second_levels as $second_level) {
                    if ($second_level->term->status == 'active') {
                        $output .= '<option value="' . $second_level->id . '" style="text-align: center">' . $second_level->name_ar . '</option>';
                    }
                }
            } else if ($request->id == 3) {
                $third_levels = SubjectClass::query()->where('season_id', $request->id)->get();
                foreach ($third_levels as $third_level) {
                    if ($third_level->term->status == 'active') {
                        $output .= '<option value="' . $third_level->id . '" style="text-align: center">' . $third_level->name_ar . '</option>';
                    }
                }
            }

            return $output;
        }
    }



    public function store(StoreLesson $request): JsonResponse
    {
        $inputs = $request->all();

        if (Lesson::create($inputs)) {
            $this->adminLog('تم اضافة درس');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit(Lesson $lesson)
    {
        $subjects = SubjectClass::query()->pluck('id')->toArray();

        $seasons = Season::get();
        $subjects_classes = SubjectClass::get();
        return view('admin.lessons.parts.edit', compact('lesson', 'subjects_classes', 'seasons','subjects'));
    }


    public function update(Lesson $lesson,StoreLesson $request): JsonResponse
    {

        if ($lesson->update($request->all())) {
            $this->adminLog('تم تحديث درس');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $lessons = Lesson::where('id', $request->id)->firstOrFail();
        $lessons->delete();
        $this->adminLog('تم حذف درس');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
