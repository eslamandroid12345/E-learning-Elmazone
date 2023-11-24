<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function index(){
        if (Auth::guard('admin')->check()){
            return redirect()->route('adminHome');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'email'   =>'required|exists:admins',
            'password'=>'required'
        ],[
            'email.exists'      => 'هذا البريد غير مسجل معنا',
            'email.required'    => 'يرجي ادخال البريد الالكتروني',
            'password.required' => 'يرجي ادخال كلمة المرور',
        ]);
        if (Auth::guard('admin')->attempt($data)){
//            toastr()->success(null,'مرحبا بعودتك');
            return response()->json(200);
        }
//        toastr()->error(null,'بيانات دخول خاطئة');
        return response()->json(405);
    }

    public function logout(){
        Auth::guard('admin')->logout();
//        toastr()->info(null,'تم تسجيل الخروج');
//        return redirect('admin/login');
        return response()->json(200);
    }

}//end class
