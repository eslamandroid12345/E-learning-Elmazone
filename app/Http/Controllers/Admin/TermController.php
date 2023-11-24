<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTerm;
use App\Models\Term;
use App\Models\Season;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TermController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {
        $terms = Term::select('*');

        $seasons = Season::all();
        if ($request->ajax()) {
            if ($request->has('season_id') && $request->season_id != ''){
                $seasonId = $request->get('season_id');
                $terms->where('season_id', $seasonId);
            }
            $terms->get();
            return Datatables::of($terms)
                ->addColumn('action', function ($terms) {
                    return '
                            <button type="button" data-id="' . $terms->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $terms->id . '" data-title="' . $terms->name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>

                       ';
                })
                ->editColumn('status', function ($terms) {
                    if($terms->status == 'active') {
                        return '<a href="' . route('activate', $terms->id) . '" class="btn btn-pill btn-success-light">مفعل</a>';
                    }
                    else {
                        return '<a href="' . route('activate', $terms->id) . '" class="btn btn-pill btn-danger-light">غير مفعل</a>';
                    }
                })
                ->editColumn('season_id', function ($terms) {
                    return '<td>'. $terms->seasons->name_ar .'</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.terms.index', compact('seasons'));
        }
    }



    public function create()
    {
        $data['seasons'] = Season::all();
        return view('admin.terms.parts.create', compact('data'));
    }


    public function store(StoreTerm $request): JsonResponse
    {
        $inputs = $request->all();
        if (Term::create($inputs)) {
            $this->adminLog('تم اضافة ترم جديدة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function activate($id){

        $term = Term::query()
        ->where('id', $id)
            ->first();

        if ($term->update([
            'status' => $term->status == 'active' ? 'not_active' : 'active'
        ])) {
            if($term->status == 'active')
            {
                toastr('تم التفعيل');
                $this->adminLog('تم تفعيل ترم');

            } else {
                toastr('تم ألغاء التفعيل');
                $this->adminLog('تم ألغاء ترم');
            }
            return redirect()->back();

        }
    }



    public function edit(Term $term)
    {
        $data['seasons'] = Season::all();
        return view('admin.terms.parts.edit', compact('term', 'data'));
    }


    public function update(StoreTerm $request, Term $term): JsonResponse
    {
        if ($term->update($request->all())) {
            $this->adminLog('تم تحديث ترم');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $terms = Term::query()->where('id', $request->id)->firstOrFail();
        $terms->delete();
        $this->adminLog('تم حذف ترم');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

    public function getAllTermsBySeason($id): array
    {

        return Term::query()
        ->where('season_id','=',$id)->pluck('name_ar','id')
            ->toArray();
    }


}
