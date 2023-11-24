<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestAudio;
use Illuminate\Http\Request;
use App\Models\Audio;
use App\Models\Lesson;
use Yajra\DataTables\Facades\DataTables;

class AudioController extends Controller
{
    // Index Start
    public function index(request $request)
    {
        if ($request->ajax()) {
            $audios = Audio::get();
            return Datatables::of($audios)
                ->addColumn('action', function ($audios) {
                    return '
                            <button type="button" data-id="' . $audios->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $audios->id . '" data-title="' . $audios->audio . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.audios.index');
        }
    }
    // Index End

    // Create Start

    public function create()
    {
        $lessons = Lesson::get();
        return view('admin.audios.parts.create', compact('lessons'));
    }

    // Create End

    // Store Start

    public function store(RequestAudio $request)
    {
        $file = $request->file('audio');
        $file->move('uploads/audios', $file->getClientOriginalName());
        $file_name = $file->getClientOriginalName();

        $insert = new Audio();
        $insert->audio = $file_name;
        $insert->lesson_id = $request->lesson_id;
        $insert->save();

        if($insert->save() == true ) {
            return response()->json(["status" => 200]);
        }
        else
        {
            return response()->json(["status" => 405]);
        }
    }

    // Store End

    // Edit Start

    public function edit(Audio $audio)
    {
        $lessons = Lesson::get();
        return view('admin.audios.parts.edit', compact('audio', 'lessons'));
    }

    //Edit End

    // Updated Start

    public function update(Request $request, Audio $audio)
    {
        $audios = Audio::findOrFail($request->id);
        if($request->has('audio')) {
            if (file_exists('uploads/audio/' . $audios->audio)) {
                unlink('uploads/audio/' . $audios->audio);
            }
            $file = $request->file('audio');
            $file->move('uploads/audios', $file->getClientOriginalName());
            $file_name = $file->getClientOriginalName();
            $audios->audio = $file_name;
        }

        $audios->lesson_id = $request->lesson_id;
        $audios->save();

        if($audios->save() == true) {
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }

    //Updated End

    // Delete Start

    public function destroy(Request $request)
    {
        $audios = Audio::where('id', $request->id)->firstOrFail();
        $audios->delete();
        return response()->json(["messsage" => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

    // Delete End

}
