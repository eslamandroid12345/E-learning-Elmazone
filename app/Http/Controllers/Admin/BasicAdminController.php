<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BasicAdminController extends Controller
{

    public $model;
    public $main_attribute;
    public $main_view_folder;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $list = $this->model::get();
            return Datatables::of($list)
                ->addColumn('action', function ($list) {
                    return '
                            <button type="button" data-id="' . $list->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $list->id . '" data-title="' . $list->{$this->main_attribute} . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->main_view_folder.".index");
        }
    }

    public function create()
    {
        return view($this->main_view_folder.'.parts.create');
    }

    public function store(StoreCountry $request)
    {
        $inputs = $request->all();

        if ($this->model::create($inputs)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit(Country $country)
    {
        return view($this->main_view_folder.'.parts.edit', compact('country'));
    }

    public function update( $request, $model)
    {
        $request = $this->requestValedation;
        $model = $this->model;
        if ($country->update($request->all())) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


}
