<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestSlider;
use App\Http\Requests\SliderStoreRequest;
use App\Http\Requests\SliderUpdateRequest;

class SliderController extends Controller
{
    use PhotoTrait , AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $sliders = Slider::get();
            return Datatables::of($sliders)
                ->addColumn('action', function ($sliders) {
                    return '
                            <button type="button" data-id="' . $sliders->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $sliders->id . '" data-title="' . $sliders->link . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('file', function ($sliders) {
                    return '<img style="width:60px;border-radius:30px" onclick="window.open(this.src)" src="' . asset($sliders->file) . '"/>';
                })
                ->editColumn('link', function ($sliders) {
                    return '<td><a class="btn btn-success" href="'. $sliders->link .'">رابط الملف</a></td>';
                })
                ->editColumn('type', function ($sliders) {
                    if($sliders->type == 'image')
                    return '<td"><i class="fa fa-image"></i> صورة</td>';
                    else
                    return '<td><i class="fa fa-video"></i> فيديو</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.sliders.index');
        }
    }

    public function create()
    {
        return view('admin.sliders.parts.create');
    }



    public function store(SliderStoreRequest $request): JsonResponse
    {
        $inputs = $request->all();
        if($request->hasFile('file')){
            if($request->type == '0')
            {
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Slider/image', 'photo');
                $inputs['type'] = 'image';
            }
            else
            {
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Slider/video', 'video');
                $inputs['type'] = 'video';
            }

        }
        if(Slider::create($inputs)) {
            $this->adminLog('تم اضافة سلايدر جديد');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }



    public function edit(Slider $slider)
    {
        return view('admin.sliders.parts.edit', compact('slider'));
    }



    public function update(SliderUpdateRequest $request, Slider $slider): JsonResponse
    {

        $inputs = $request->all();

        if ($request->hasFile('file')) {
            if($request->type == 'image')
            {
                if (file_exists($slider->file)) {
                    unlink($slider->file);
                }
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Slider/image', 'photo');
                $inputs['type'] = 'image';
            }
            else
            {
                if (file_exists($slider->file)) {
                    unlink($slider->file);
                }
                $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/Slider/video', 'video');
                $inputs['type'] = 'video';
            }
        }


        if($slider->update($inputs)){
            $this->adminLog('تم تحديث سلايدر ');
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request)
    {
        $sliders = Slider::where('id', $request->id)->firstOrFail();
        $sliders->delete();
        $this->adminLog('تم حذف سلايدر');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
