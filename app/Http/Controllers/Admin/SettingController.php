<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\PhotoTrait;

class SettingController extends Controller
{
    use PhotoTrait;
    use AdminLogs;

    public function index()
    {
        $settings = Setting::find(1);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): JsonResponse
    {
        $settings = Setting::findOrFail($request->id);

        $inputs = $request->all();

        if ($request->file('teacher_image')) {

            if (file_exists(public_path('teacher_image/'.$settings->teacher_image)) && $settings->teacher_image != null) {
                unlink(public_path('teacher_image/'.$settings->teacher_image));
            }
            $inputs['teacher_image'] = $this->saveImageInFolder($request->file('teacher_image'),'teacher_image');
        }

        if ($settings->update($inputs)){
            $this->adminLog('تم تحديث الاعدادات');
            return response()->json(['status' => 200]);
        }
        else{
            return response()->json(['status' => 405]);
        }
    }



}
