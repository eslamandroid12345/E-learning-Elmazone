<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MotivationalExport;
use App\Http\Controllers\Controller;
use App\Imports\MotivationalImport;
use App\Models\MotivationalSentences;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\DataTables;

class MotivationalSentencesController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $sentences = MotivationalSentences::get();
            return Datatables::of($sentences)
                ->addColumn('action', function ($sentences) {
                    return '
                            <button type="button" data-id="' . $sentences->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $sentences->id . '" data-title="' . $sentences->title_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.motivational_sentences.index');
        }
    }



    public function create()
    {
        return view('admin.motivational_sentences.parts.create');
    }


    public function store(Request $request): JsonResponse
    {
        $request->validate([

            'title_ar' => 'required',
            'title_en' => 'required',
            'percentage_from' => 'required|unique:motivational_sentences,percentage_from',
            'percentage_to' => 'required',
        ]);


        $inputs = $request->all();
        if (MotivationalSentences::create($inputs)) {
            $this->adminLog('تم اضافة جمل تحفيزية');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function edit(MotivationalSentences $motivational)
    {
        return view('admin.motivational_sentences.parts.edit', compact('motivational'));
    }


    public function update(Request $request, MotivationalSentences $motivational): JsonResponse
    {
        $request->validate([

            'title_ar' => 'required',
            'title_en' => 'required',
            'percentage_from' => 'required|unique:motivational_sentences,percentage_from,' . request()->id,
            'percentage_to' => 'required',
        ]);

        if ($motivational->update($request->all())) {
            $this->adminLog('تم تحديث جمل تحفيزية');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request)
    {

        $sentences = MotivationalSentences::where('id', $request->id)->firstOrFail();
        $sentences->delete();
        $this->adminLog('تم حذف جمل تحفيزية');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    // \Symfony\Component\HttpFoundation\
    public function motivationalExport(): BinaryFileResponse
    {
        return Excel::download(new MotivationalExport, 'Motivational.xlsx');

    }

    public function motivationalImport(Request $request): \Illuminate\Http\JsonResponse
    {
        $import = Excel::import(new MotivationalImport(), $request->exelFile);
        if ($import) {
            $this->adminLog('تم استيراد جمل تحفيزية');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 500]);
        }
    }

}
