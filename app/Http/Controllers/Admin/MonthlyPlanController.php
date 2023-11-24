<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use App\Models\Season;
use App\Traits\AdminLogs;
use App\Models\MonthlyPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\RequestMonthlyPlan;
use App\Http\Requests\MonthlyPlanStoreRequest;
use App\Http\Requests\MonthlyPlanUpdateRequest;

class MonthlyPlanController extends Controller
{
    use AdminLogs;

    // Index START

    public function index(request $request)
    {
        if ($request->ajax()) {
            $monthlyPlans = MonthlyPlan::get();
            return Datatables::of($monthlyPlans)
                ->addColumn('action', function ($monthlyPlans) {
                    return '
                            <button type="button" data-id="' . $monthlyPlans->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $monthlyPlans->id . '" data-title="' . $monthlyPlans->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.monthly_plans.index');
        }
    }

    // End Index

    // Create START

    public function create()
    {
        $data['seasons'] = Season::query()->select('id', 'name_ar')->get();
        $data['terms'] = Term::query()->select('id', 'name_ar')->get();
        return view('admin.monthly_plans.parts.create', compact('data'));
    }

    // Create END


    // Store START

    public function store(MonthlyPlanStoreRequest $request)
    {
        $inputs = $request->all();
        $existingPlan = MonthlyPlan::where('season_id', $inputs['season_id'])
            ->where('term_id', $inputs['term_id'])
            ->where('start', $inputs['start'])
            ->where('end', $inputs['end'])
            ->first();

        if ($existingPlan) {
            return response()->json(['status' => 405, 'errors' => ['plan' => ['This plan already exists.']]], 405);
        }

        if (MonthlyPlan::create($inputs)) {
            $this->adminLog('تم اضافة خطة شهرية');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    // Store END

    // Edit START

    public function edit(MonthlyPlan $monthlyPlan)
    {
        $data['seasons'] = Season::query()->select('id', 'name_ar')->get();
        $data['terms'] = Term::query()->select('id', 'name_ar')->get();
        return view('admin.monthly_plans.parts.edit', compact('monthlyPlan', 'data'));
    }
    // Edit END

    // Update START

    public function update(MonthlyPlanUpdateRequest $request, MonthlyPlan $monthlyPlan)
    {
        if ($monthlyPlan->update($request->all())) {
            $this->adminLog('تم تحديث خطة شهرية');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    // Update END

    // Delete START

    public function destroy(Request $request)
    {
        $monthlyPlan = MonthlyPlan::where('id', $request->id)->firstOrFail();
        $monthlyPlan->delete();
        $this->adminLog('تم حذف خطة شهرية');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

    // Delete END
}
