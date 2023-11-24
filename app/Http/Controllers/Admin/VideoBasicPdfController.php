<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoBasicPdf;
use App\Http\Requests\UpdateVideoBasicPdf;
use App\Models\VideoBasicPdfUploads;
use App\Models\VideoBasic;
use App\Models\VideoResource;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VideoBasicPdfController extends Controller
{
    use PhotoTrait;
    // Index Start
    public function index(request $request)
    {
        $video_basics_pdfs = VideoBasicPdfUploads::all();
        if ($request->ajax()) {
            return Datatables::of($video_basics_pdfs)
                ->addColumn('action', function ($video_basics_pdfs) {
                    return '
                            <button type="button" data-id="' . $video_basics_pdfs->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $video_basics_pdfs->id . '" data-title="' . $video_basics_pdfs->name_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>

                       ';
                })
                ->editColumn('type', function ($video_basics_pdfs) {
                    return '<td>'. ($video_basics_pdfs->type == 'video_resource' ? "مصدر الفيديو" : "فيديو تأسيسي") .'</td>';

                })
                ->editColumn('video_basic_id', function ($video_basics_pdfs) {
                    if($video_basics_pdfs->video_basic_id) {
                        return '<td>'. ($video_basics_pdfs->video_basic_id ? $video_basics_pdfs->video_basic_id : '____') .'</td>';
                    }
                    else
                    {
                        return '____';
                    }
                })
                ->editColumn('video_resource_id', function ($video_basics_pdfs) {
                    if($video_basics_pdfs->video_resource_id) {
                        return '<td>'. ($video_basics_pdfs->video_resource_id ? $video_basics_pdfs->video_resource_id : '____') .'</td>';
                    }
                    else
                    {
                        return '____';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.video_basic_pdf.index');
        }
    }

    // Index End



    // Create Start

    public function create()
    {
        $data['video_basics'] = VideoBasic::all();
        $data['Video_resources'] = VideoResource::all();
        return view('admin.video_basic_pdf.parts.create', compact('data'));
    }
    // Create End

    // Store Start

    public function store(Request $request)
    {
        $inputs = $request->all();
        if($request->has('files')){
            foreach($request->file('files') as $file)
            {
                $inputs['pdf_links'][] = $this->saveImage($file,'videos_basics_pdf/','photo');
            }
        }
        if(VideoBasicPdfUploads::create($inputs)) {
            return response()->json(['status' => 200]);
        }
        else
        {
            return response()->json(['status' => 405]);
        }
    }

    // Store End


    // Edit Start

    public function edit(VideoBasicPdfUploads $videoBasicPdf)
    {
        $data['video_basics'] = VideoBasic::all();
        $data['Video_resources'] = VideoResource::all();
        return view('admin.video_basic_pdf.parts.edit', compact('videoBasicPdf', 'data'));
//        dd('hi');
    }


    // Edit End

    // Update Start

    public function update(Request $request, VideoBasicPdfUploads $videoBasicPdf)
    {
        $inputs = $request->all();

        if($request->type == 'video_resource') {
            $inputs['video_basic_id'] = null;
        }
        if ($request->type == 'video_basic'){
            $inputs['video_resource_id'] = null;
        }

        if($request->has('files')){
            foreach($request->file('files') as $file) {
                $inputs['pdf_links'][] = $this->saveImage($file,'videos_basics_pdf/','photo');
            }
        }

        if ($videoBasicPdf->update($inputs)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }


    }

    // Update End

    // Destroy Start

    public function destroy(Request $request)
    {
        $terms = VideoBasicPdfUploads::where('id', $request->id)->firstOrFail();
        $terms->delete();
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

    // Destroy End
}
