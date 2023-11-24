<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Section;
use App\Http\Requests\RequestSection;

class SectionController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $sections = Section::get();
            return Datatables::of($sections)
                ->addColumn('action', function ($sections) {
                    return '
                            <button type="button" data-id="' . $sections->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $sections->id . '" data-title="' . $sections->section_name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })

                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.sections.index');
        }
    }


    public function create()
    {
        return view('admin.sections.parts.create');
    }



    public function store(RequestSection $request): JsonResponse
    {
        $inputs = $request->all();
        if (Section::create($inputs)) {
            $this->adminLog('تم اضافة قاعة جديدة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(Section $section)
    {
        return view('admin.sections.parts.edit', compact('section'));
    }



    public function update(Request $request, Section $section): JsonResponse
    {
        if ($section->update($request->all())) {
            $this->adminLog('تم تحديث قاعة ');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $sections = Section::where('id', $request->id)->firstOrFail();
        $sections->delete();
        $this->adminLog('تم حذف قاعة ');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
