<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\TextExamUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;
use Yajra\DataTables\DataTables;

class TextExamUserController extends Controller
{
    // Index START
    public function index(request $request)
    {
        if ($request->ajax()) {
            $text_exam_users = TextExamUser::get();
            return Datatables::of($text_exam_users)
                ->addColumn('action', function ($text_exam_users) {
                    return '
                            <button type="button" data-id="' . $text_exam_users->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $text_exam_users->id . '" data-title="' . $text_exam_users->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.text_exam_users.index');
        }
    }

    // End Index

    // Create Start

    public function create()
    {
        $data['users'] = User::all();
        $data['questions'] = Question::all();
        $data['online_exams'] = OnlineExam::all();
        return view('admin.text_exam_users.parts.create', compact('data'));
    }

    // Create End

    //Show Question Start

    public function showQuestions(Request $request)
    {
        if ($request->ajax()) {
            $output = '<option value="" style="text-align: center">اختار</option>';
            if ($request->id == 1) {
                $first_levels = SubjectClass::where('season_id', $request->id)->get();
                foreach ($first_levels as $first_level) {
                    if ($first_level->term->status == 'active') {
                        $output .= '<option value="' . $first_level->id . '" style="text-align: center">' . $first_level->name_ar . '</option>';
                    }
                }
              }
// else if ($request->id == 2) {
//                $second_levels = SubjectClass::where('season_id', $request->id)->get();
//                foreach ($second_levels as $second_level) {
//                    if ($second_level->term->status == 'active') {
//                        $output .= '<option value="' . $second_level->id . '" style="text-align: center">' . $second_level->name_ar . '</option>';
//                    }
//                }
//            } else if ($request->id == 3) {
//                $third_levels = SubjectClass::where('season_id', $request->id)->get();
//                foreach ($third_levels as $third_level) {
//                    if ($third_level->term->status == 'active') {
//                        $output .= '<option value="' . $third_level->id . '" style="text-align: center">' . $third_level->name_ar . '</option>';
//                    }
//                }
//            }

            return $output;
        }
    }

    // Show Question End
}
