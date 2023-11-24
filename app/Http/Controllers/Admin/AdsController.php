<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestAds;
use App\Models\Ads;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdsController extends Controller
{
    use PhotoTrait , AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $ads = Ads::get();
            return Datatables::of($ads)
                ->addColumn('action', function ($ads) {
                    return '
                            <button type="button" data-id="' . $ads->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $ads->id . '" data-title="' . $ads->name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                
                ->editColumn('file', function ($sliders) {
                    return '<img style="width:60px;border-radius:30px" onclick="window.open(this.src)" src="' . asset($sliders->file) . '"/>';
                })
                ->editColumn('status', function ($ads) {
                    if($ads->status == 'true') {
                        return '<a href="' . route('activateAds', $ads->id) . '" class="btn btn-pill btn-success-light">مفعل</a>';
                    }
                    else {
                        return '<a href="' . route('activateAds', $ads->id) . '" class="btn btn-pill btn-danger-light">غير مفعل</a>';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.ads.index');
        }
    }

    // End Index

    // Activate

    public function activateAds($id){

        $ads = Ads::query()->where('id', $id)->first();

        if ($ads->update([
            'status' => $ads->status == 'true' ? 'false' : 'true'
        ])) {
            if($ads->status == 'true')
            {
                toastr('تم التفعيل');
            }
            else
            {
                toastr('تم ألغاء التفعيل');
            }
            return view('admin.ads.index');
        }
    }



    public function create()
    {
        return view('admin.ads.parts.create');
    }



    public function store(RequestAds $request)
    {
        $inputs = $request->all();
        if($request->hasFile('file')){
            if($request->type == '0')
            {
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Ads/image', 'photo');
                $inputs['type'] = 'image';
            }
            else
            {
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Ads/video', 'video');
                $inputs['type'] = 'video';
            }

        }

        if(Ads::create($inputs)) {
            $this->adminLog('تم اضافة اعلان');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(Ads $ad)
    {
        return view('admin.ads.parts.edit', compact('ad'));
    }

    // Update Start

    public function update(RequestAds $request, Ads $ad)
    {

        $inputs = $request->all();

        if ($request->hasFile('file')) {
            if($request->type == 'image')
            {
                if (file_exists($ad->file)) {
                    unlink($ad->file);
                }
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Ads/image', 'photo');
                $inputs['type'] = 'image';
            }
            else
            {
                if (file_exists($ad->file)) {
                    unlink($ad->file);
                }
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Ads/video', 'video');
                $inputs['type'] = 'video';
            }
        }

        if($ad->update($inputs)){
            $this->adminLog('تم تحديث اعلان');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request)
    {
        $sliders = Ads::where('id', $request->id)->firstOrFail();
        $sliders->delete();
        $this->adminLog('تم حذف اعلان');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
