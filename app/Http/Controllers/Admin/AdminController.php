<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    use PhotoTrait, AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::query()->latest()->get();
            return Datatables::of($admins)
                ->addColumn('action', function ($admins) {
                    if ($admins->id == 1) {
                        return '
                            <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                      ';
                    } else {
                        return '
                            <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $admins->id . '" data-title="' . $admins->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                    }
                })
                ->editColumn('image', function ($admins) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset($admins->image) . '">
                    ';
                })
                ->addColumn('roles', function ($admins) {
                    $adminRole = $admins->roles->pluck('name', 'name')->first();
                    if ($adminRole == 'سوبر ادمن') {
                        return '<span class="badge badge-primary-gradient">' . $adminRole . '</span>';
                    } else {
                        return '<span class="badge badge-info">' . $adminRole . '</span>';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/admin/index');
        }
    }


    public function delete(Request $request)
    {
        $admin = Admin::query()
        ->where('id', $request->id)
            ->first();

        if ($admin == auth()->guard('admin')->user()) {
            return response(['message' => "لا يمكن حذف المشرف المسجل به !", 'status' => 501], 200);
        } else {
            if (file_exists($admin->image)) {
                unlink($admin->image);
            }
            $admin->delete();
            $this->adminLog('تم حذف مشرف');
            return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
        }
    }

    public function myProfile()
    {
        $admin = auth()->guard('admin')->user();
        $roleName = $admin->roles->pluck('name', 'name')->first();
        return view('admin.admin.profile', compact('admin', 'roleName'));
    }


    public function create()
    {
        $roles = Role::all();
        return view('admin/admin.parts.create', compact('roles'));
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        $inputs = $request->except('roles');
        if ($request->has('image')) {
            $inputs['image'] = $this->saveImage($request->image, 'assets/uploads/admins');
        }
        $inputs['password'] = Hash::make($request->password);
        $admin = Admin::create($inputs);
        $admin->assignRole($request->input('roles'));
        if ($admin->save()) {
            $this->adminLog('تم اضافة مشرف');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $adminRole = $admin->roles->pluck('id', 'id')->all();
        return view('admin/admin.parts.edit', compact('admin', 'roles', 'adminRole'));
    }


    public function update(UpdateAdminRequest $request, $id): JsonResponse
    {
        $inputs = $request->except('id', 'roles');

        $admin = Admin::query()->findOrFail($id);

        if ($request->has('image')) {
            if (file_exists($admin->image)) {
                unlink($admin->image);
            }
            $inputs['image'] = $this->saveImage($request->image, 'assets/uploads/admins');
        }
        if ($request->has('password') && $request->password != null)
            $inputs['password'] = Hash::make($request->password);
        else
            unset($inputs['password']);
        $admin->update($inputs);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $admin->assignRole($request->input('roles'));

        if ($admin->save()) {
            $this->adminLog('تم تحديث مشرف');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }
}
