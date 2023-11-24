<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{

    use AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $cities = City::get();
            return Datatables::of($cities)
                ->addColumn('action', function ($cities) {
                    return '
                            <button type="button" data-id="' . $cities->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $cities->id . '" data-title="' . $cities->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.cities.index');
        }
    }

    public function create()
    {
        return view('admin.cities.parts.create');
    }

    public function store(CityRequest $request): JsonResponse
    {
        $inputs = $request->all();

        if (City::create($inputs)) {
            $this->adminLog('تم اضافة دولة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


     public function edit(City $city)
     {
         return view('admin.cities.parts.edit', compact('city'));
     }

     public function update(CityRequest $request, City $city): JsonResponse
     {

         if ($city->update($request->all())) {
             $this->adminLog('تم تحديث الدولة');
             return response()->json(['status' => 200]);
         } else {
             return response()->json(['status' => 405]);
         }
     }

     public function destroy(Request $request)
     {
         $citites = City::where('id', $request->id)->firstOrFail();
         $citites->delete();
         $this->adminLog('تم حذف محافظة');
         return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
     }


}
