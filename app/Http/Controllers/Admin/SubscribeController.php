<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestSubscribe;
use App\Models\Season;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Subscribe;
use App\Models\Term;

class SubscribeController extends Controller
{
    use AdminLogs;
    public function index(request $request)
    {
        if ($request->ajax()) {
            $subscribes = Subscribe::get();
            return Datatables::of($subscribes)
                ->addColumn('action', function ($subscribes) {
                    return '
                            <button type="button" data-id="' . $subscribes->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $subscribes->id . '" data-title="' . $subscribes->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('term_id', function ($subscribes) {
                    return  $subscribes->term->name_ar;
                })
                ->editColumn('season_id', function ($subscribes) {
                    return $subscribes->season->name_ar;
                })
                ->editColumn('free', function ($subscribes) {
                    if($subscribes->free == 'yes')
                    return '<td>نعم</td>';
                    else
                    return '<td>لا</td>';
                })
                ->editColumn('price_in_center', function ($subscribes) {
                    return $subscribes->price_in_center;
                })
                ->editColumn('price_out_center', function ($subscribes) {
                    return $subscribes->price_out_center;
                })
                ->editColumn('month', function ($subscribes) {
                    return $subscribes->month;
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.subscribes.index');
        }
    }


    public function create()
    {
        $seasons = Season::all();
        return view('admin.subscribes.parts.create', compact('seasons'));
    }


    public function store(RequestSubscribe $request): JsonResponse
    {
        $inputs = $request->all();

        if($request->has('free')) {
            if($request->free == '1') {
                $inputs['free'] = 'yes';
            }
            else {
                $inputs['free'] = 'no';
            }
        }

        if (Subscribe::create($inputs)) {
            $this->adminLog('تم اضافة باقة جديدة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(Subscribe $subscribe)
    {
        $seasons = Season::get();
        $terms = Term::get();
        return view('admin.subscribes.parts.edit', compact('seasons','terms', 'subscribe'));
    }

    public function update(Subscribe $subscribe, RequestSubscribe $request): JsonResponse
    {
        $inputs = $request->all();

        if($request->has('free')) {
            if($request->free == '1') {
                $inputs['free'] = 'yes';
            }
            else {
                $inputs['free'] = 'no';
            }
        }

        if ($subscribe->update($inputs)) {
            $this->adminLog('تم تحديث باقة ');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request): JsonResponse
    {
        $subject_class = Subscribe::where('id', $request->id)->firstOrFail();
        $subject_class->delete();
        $this->adminLog('تم حذف باقة ');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
