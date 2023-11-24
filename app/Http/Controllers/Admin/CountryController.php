<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountry;
use App\Models\City;
use App\Models\Country;
use App\Traits\AdminLogs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $countries = Country::get();
            return Datatables::of($countries)
                ->addColumn('action', function ($countries) {
                    return '
                            <button type="button" data-id="' . $countries->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $countries->id . '" data-title="' . $countries->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->editColumn('city_id', function($countries) {
                    return '<td>'. $countries->city->name_ar .'</td>';
                })
                ->make(true);
        } else {
            return view('admin.countries.index');
        }
    }

    public function create()
    {
        $data['cities'] = City::all();
        return view('admin.countries.parts.create', compact('data'));
    }


    public function store(StoreCountry $request): JsonResponse
    {
        $inputs = $request->all();

        if (Country::create($inputs)) {
            $this->adminLog('تم اضافة محافظة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(Country $country)
    {
        $data['cities'] = City::all();
        return view('admin.countries.parts.edit', compact('country', 'data'));
    }


    public function update(StoreCountry $request, Country $country): JsonResponse
    {
        if ($country->update($request->all())) {
            $this->adminLog('تم تحديث محافظة');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function destroy(Request $request)
    {
        $countries = Country::where('id', $request->id)->firstOrFail();
        $countries->delete();
        $this->adminLog('تم حذف محافظة');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
