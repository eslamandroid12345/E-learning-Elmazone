<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLog;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminLogController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $startDate = $request->to;
        $endDate = $request->from;
        $logList = AdminLog::select('*');


        if ($request->has('to') && $request->has('from') && $startDate !== $now && $endDate !== $now) {
            $startDate = $request->get('from');
            $endDate = $request->get('to');
            $logList->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        } else {
            $startDate = $now;
            $endDate = $now;
        }

        if ($request->ajax()) {

            $logs = $logList->get();

            return Datatables::of($logs)
                ->addColumn('button', function ($logs) {
                    return '<button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $logs->id . '" data-title="' . $logs->action . '">
                                    <i class="fas fa-trash"></i>
                            </button>';
                })
                ->editColumn('admin_id', function ($logs) {
                    return $logs->admin->name;
                })
                ->editColumn('created_at', function ($logs) {
                    return Carbon::parse($logs->created_at)->format('Y-m-d H:i');
                })
                ->addColumn('role', function ($logs) {
                    $adminRole = $logs->admin->roles->pluck('name', 'name')->first();
                    if ($adminRole == 'سوبر ادمن') {
                        return '<span class="badge badge-primary-gradient">' . $adminRole . '</span>';
                    } else {
                        return '<span class="badge badge-info">' . $adminRole . '</span>';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            AdminLog::where('seen', '=', 0)->update(['seen' => 1]);
            return view('admin.admin.admin_logs', compact('startDate', 'endDate'));
        }
    }

    public function delete(Request $request)
    {
        $admin = AdminLog::where('id', $request->id)->first();
        $admin->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

    public function deleteAll(Request $request)
    {
        AdminLog::query()
            ->whereDate('created_at','>=',$request->get('from'))
            ->whereDate('created_at','<=',$request->get('to'))
            ->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }
}
