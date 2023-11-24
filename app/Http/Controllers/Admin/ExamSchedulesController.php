<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamScheduleRequest;
use App\Models\ExamSchedule;
use App\Models\Term;
use App\Models\Season;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ExamSchedulesController extends Controller
{
    use PhotoTrait;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $exam_schedules = ExamSchedule::get();
            return Datatables::of($exam_schedules)
                ->addColumn('action', function ($exam_schedules) {
                    return '
                             <button type="button" data-id="' . $exam_schedules->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                             <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                     data-id="' . $exam_schedules->id . '" data-title="' . $exam_schedules->title_ar . '">
                                     <i class="fas fa-trash"></i>
                             </button>
                        ';
                })
                ->editColumn('season_id', function ($exam_schedules) {
                    return $exam_schedules->season->name_ar;
                })
                ->editColumn('term_id', function ($exam_schedules) {
                    return $exam_schedules->term->name_ar;
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.exam_schedules.index');
        }
    }

    public function create()
    {
        $data['seasons'] = Season::get();
        $data['terms'] = Term::get();
        return view('admin.exam_schedules.parts.create', compact('data'));
    }


    public function store(ExamScheduleRequest $request): JsonResponse
    {

        $inputs = $request->all();

        if($request->hasFile('image')){
            $inputs['image'] = $this->saveImage($request->image, 'exam_schedules', 'photo');
        }

        if (ExamSchedule::create($inputs)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(ExamSchedule $exam_schedule)
    {
        $data['seasons'] = Season::get();
        $data['terms'] = Term::get();
        return view('admin.exam_schedules.parts.edit', compact('exam_schedule', 'data'));
    }


    public function update(ExamScheduleRequest $request,ExamSchedule $exam_schedule): JsonResponse
    {

        if($request->hasFile('image')){
            $inputs['image'] = $this->saveImage($request->image, 'exam_schedules', 'photo');
        }
        if ($exam_schedule->update($request->all())) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function destroy(Request $request)
    {
        $exam_schedule = ExamSchedule::where('id', $request->id)->firstOrFail();
        $exam_schedule->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
