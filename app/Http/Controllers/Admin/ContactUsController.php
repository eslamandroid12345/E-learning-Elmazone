<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestContactUs;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactUsController extends Controller
{
    // Index Start
    public function index(request $request)
    {
        if ($request->ajax()) {
            $contact_us = ContactUs::get();
            return Datatables::of($contact_us)
                ->addColumn('action', function ($contact_us) {
                    return '
                            <button type="button" data-id="' . $contact_us->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $contact_us->id . '" data-title="' . $contact_us->image . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.contact_us.index');
        }
    }
    // Index End

    public function create()
    {
        return view('admin.contact_us.parts.create');
    }

    // Create End

    // Store Start

    public function store(RequestContactUs $request)
    {
        $inputs = $request->all();

        if(ContactUs::create($inputs)) {
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }

    // Store End

    // Edit Start

    public function edit(ContactUs $contactU)
    {
        return view('admin.contact_us.parts.edit', compact('contactU'));
    }

    // Edit End

    public function update(RequestContactUs $request, ContactUs $contactU)
    {

        $inputs = $request->all();


        if($contactU->update($inputs)){
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }

    // Update End
}
