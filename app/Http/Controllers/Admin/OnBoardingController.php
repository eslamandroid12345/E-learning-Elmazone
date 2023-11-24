<?php

namespace App\Http\Controllers\Admin;

use App\Traits\AdminLogs;
use App\Models\OnBoarding;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\OnBoardingStoreRequest;
use App\Http\Requests\OnBoardingUpdateRequest;

class OnBoardingController extends Controller
{
    use PhotoTrait , AdminLogs;
    // Index Start
    public function index(request $request)
    {
        if ($request->ajax()) {
            $on_boarding = OnBoarding::get();
            return Datatables::of($on_boarding)
                ->addColumn('action', function ($on_boarding) {
                    return '
                            <button type="button" data-id="' . $on_boarding->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                       ';
                })
                ->editColumn('image', function ($on_boarding) {
                    return '<img style="width:60px;border-radius:30px" onclick="window.open(this.src)" src="' . asset($on_boarding->image) . '"/>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.on_boarding.index');
        }
    }


    public function create()
    {
        return view('admin.on_boarding.parts.create');
    }



    public function store(OnBoardingStoreRequest $request)
    {
        $inputs = $request->all();
        if($request->hasFile('image')){
            $inputs['image'] = $this->saveImage($request->image, 'assets/uploads/onBoarding/image', 'photo');
        }
        if(OnBoarding::create($inputs)) {
            $this->adminLog('تم اضافة شاشات افتتاحيه');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }



    public function edit(OnBoarding $onBoarding)
    {
        return view('admin.on_boarding.parts.edit', compact('onBoarding'));
    }


    public function update(OnBoardingUpdateRequest $request, OnBoarding $onBoarding)
    {

        $inputs = $request->all();

        if ($request->hasFile('image')) {
            if (file_exists($onBoarding->image)) {
                unlink($onBoarding->image);
            }
            $inputs['image'] = $this->saveImage($request->image, 'assets/uploads/onBoarding/image', 'photo');
        }

        if($onBoarding->update($inputs)){
            $this->adminLog('تم تحديث شاشات افتتاحيه');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request)
    {
        $on_boarding = OnBoarding::where('id', $request->id)->firstOrFail();
        $on_boarding->delete();
        $this->adminLog('تم حذف شاشات افتتاحيه');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
