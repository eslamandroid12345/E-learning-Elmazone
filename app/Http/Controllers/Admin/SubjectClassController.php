<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectClasses;
use App\Models\SubjectClass;
use App\Models\Term;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Season;

class SubjectClassController extends Controller
{
    use PhotoTrait , AdminLogs;

    public function index(Request $request)
    {
        $subjects_classes_list = SubjectClass::select('*');
        $terms = Term::all();
        $seasons = Season::all();
        if ($request->ajax()) {
            if ($request->has('term_id') && $request->term_id != '') {
                $term = $request->get('term_id');
                $subjects_classes_list->where('term_id', $term);
            }
            $subjects_classes = $subjects_classes_list->get();
            return Datatables::of($subjects_classes)
                ->addColumn('action', function ($subjects_classes) {
                    return '
                            <button type="button" data-id="' . $subjects_classes->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $subjects_classes->id . '" data-title="' . $subjects_classes->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('background_color', function ($subjects_classes) {
                    return '<input type="color" class="form-control" name="background_color"
                           value="' . $subjects_classes->background_color . '" disabled>';
                })
                ->editColumn('image', function ($subjects_classes) {
                    return '<img style="width:60px;border-radius:30px" onclick="window.open(this.src)" src="' . asset('classes/' . $subjects_classes->image) . '"/>';
                })
                ->editColumn('term_id', function ($subjects_classes) {
                    return '<td>' . $subjects_classes->term->name_ar . '</td>';
                })
                ->editColumn('season_id', function ($subjects_classes) {
                    return '<td>' . $subjects_classes->season->name_ar . '</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.subject_classes.index', compact('terms', 'seasons'));
        }
    }



    public function seasonSort(Request $request): string
    {

        $season = $request->id;
        $subjects = Term::where('season_id', $season)->get();

        $output = '<option value="" style="text-align: center">اختر الترم</option>';

        foreach ($subjects as $subject) {
            $output .= '<option value="' . $subject->id . '" style="text-align: center">' . $subject->name_ar . ' </option>';
        }
        if ($subjects->count() > 0) {
            return $output;
        } else {
            return '<option value="">لا يوجد ترمات</option>';
        }

    }



    public function create()
    {
        $terms = Term::get();
        $seasons = Season::get();
        return view('admin.subject_classes.parts.create', compact('terms', 'seasons'));
    }



    public function store(StoreSubjectClasses $request): JsonResponse
    {
        $inputs = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();

            $file->move('classes/', $filename);
            $inputs['image'] = $filename;
        }

        if (SubjectClass::create($inputs)) {
            $this->adminLog('تم اضافة وحدة جديدة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(SubjectClass $subjectsClass)
    {
        $seasons = Season::query()->get();
        $terms = Term::query()->get();
        return view('admin.subject_classes.parts.edit', compact('subjectsClass', 'terms', 'seasons'));
    }


    public function update(SubjectClass $subjectsClass, StoreSubjectClasses $request): JsonResponse
    {
        $inputs = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();

            $file->move('classes/', $filename);
            $inputs['image'] = $filename;
        }
        if ($subjectsClass->update($inputs)) {
            $this->adminLog('تم تحديث وحدة ');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $subject_class = SubjectClass::where('id', $request->id)->firstOrFail();
        $subject_class->delete();
        $this->adminLog('تم حذف وحدة');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
