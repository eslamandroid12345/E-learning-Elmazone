<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateItemRequest;
use App\Models\Term;
use App\Models\Guide;
use App\Models\Lesson;
use App\Models\Season;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use App\Models\SubjectClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Tymon\JWTAuth\Claims\Subject;
use App\Http\Requests\RequestGuide;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuideStoreRequest;
use App\Http\Requests\GuideUpdateRequest;
use App\Http\Requests\AddItemStoreRequest;

class GuideController extends Controller
{

    use PhotoTrait , AdminLogs;

    // Index Start
    public function index(request $request)
    {
        if ($request->ajax()) {
            $guides = Guide::where('from_id', '=', null);
            return Datatables::of($guides)
                ->addColumn('action', function ($guides) {
                    return '
                            <button type="button" data-id="' . $guides->id . '" class="btn btn-pill btn-info-light editBtn">تعديل</button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $guides->id . '" data-title="' . $guides->title_ar . '">
                                   حذف
                            </button>
                            <a href="' . route('indexItem', $guides->id) . '" class="btn btn-pill btn-success-light addItem">اضافة عنصر</a>
                       ';
                })
                ->editColumn('background_color', function ($guides) {
                    return '<input type="color" class="form-control" name="color"
                           value="'. $guides->background_color .'" disabled>';
                })
                ->editColumn('term_id', function ($guides) {
                    return  $guides->term->name_ar;
                })
                ->editColumn('season_id', function ($guides) {
                    return $guides->season->name_ar;
                })
                ->editColumn('file', function ($items) {

                    return '<a href="' . asset($items->file) . '">
                                رابط الملف الورقي للكتاب
                            </a>';

                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('admin.guides.index');
    }


    public function create()
    {
        $terms = Term::all();
        $seasons = Season::all();
        return view('admin.guides.parts.create', compact('terms', 'seasons'));
    }



    public function subjectSort(Request $request): string
    {

        $terms = $request->id;
        $subjects = SubjectClass::where('term_id', $terms)->get();

        $output = '<option value="" style="text-align: center">اختر الوحدة</option>';

        foreach ($subjects as $subject) {
            $output .= '<option value="' . $subject->id . '" style="text-align: center">' . $subject->name_ar . ' </option>';
        }
        if ($subjects->count() > 0) {
            return $output;
        } else {
            return '<option value="" style="text-align: center">لا يوجد وحدات</option>';
        }

    }



    public function lessonSort(Request $request): string
    {

        $subject = $request->id;
        $lessons = Lesson::query()
        ->where('subject_class_id', $subject)
            ->select('id','name_ar')
            ->get();

        $output = '<option value="" style="text-align: center">اختر الدرس</option>';

        foreach ($lessons as $lesson) {
            $output .= '<option value="' . $lesson->id . '" style="text-align: center">' . $lesson->name_ar . ' </option>';
        }
        if ($lessons->count() > 0) {
            return $output;
        } else {
            return '<option value="" style="text-align: center">لا يوجد دروس تابعه لهذا الفصل</option>';
        }

    }



    public function store(GuideStoreRequest $request): JsonResponse
    {
        $inputs = $request->all();

        if($request->hasFile('file')){
            $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/guides/file', 'photo');
        }

        if($request->hasFile('icon')){
            $inputs['icon'] = $this->saveImage($request->icon, 'assets/uploads/guides/icon', 'photo');
        }

        if (Guide::create($inputs)) {
            $this->adminLog('تم اضافة مصادر ومراجع');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(Guide $guide)
    {
        $seasons = Season::all();
        return view('admin.guides.parts.edit', compact('guide', 'seasons'));
    }


    //update guide ELee
    public function update(Guide $guide, GuideUpdateRequest $request): JsonResponse
    {
        $inputs = $request->all();

        if($request->hasFile('file')){
            $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/guides/file', 'photo');
        }

        if($request->hasFile('icon')){
            $inputs['icon'] = $this->saveImage($request->icon, 'assets/uploads/guides/icon', 'photo');
        }

        if ($guide->update($inputs)) {
            $this->adminLog('تم تحديث مصادر ومراجع');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request): JsonResponse
    {
        $guide = Guide::query()->where('id', $request->id)->firstOrFail();
        if(file_exists(public_path($guide->file))){
            unlink(public_path($guide->file));
        }
        $guide->delete();
        $this->adminLog('تم حذف مصادر ومراجع');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function indexItem(Request $request, $id)
    {
        if ($request->ajax()) {
            $items = Guide::where('from_id', $id)->get();
            return Datatables::of($items)
                ->addColumn('action', function ($items) {
                    return '
                    <button type="button" data-id="' . $items->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $items->id . '" data-title="' . $items->title_ar . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('subject_class_id', function ($items) {
                    return '<td>' . @$items->subjectClass->name_ar . '</td>';
                })
                ->editColumn('lesson_id', function ($items) {
                    return '<td>' . @$items->lesson->name_ar . '</td>';
                })

                ->editColumn('file', function ($items) {

                        return '<a href="' . asset($items->file) . '">
                                رابط ملف المراجعه
                            </a>';

                })
                ->editColumn('answer_video_file', function ($items) {
                    if ($items->answer_video_file){

                        return '<a href="' . asset($items->answer_video_file) . '">
                                رابط فيديو الاجابه
                            </a>';
                    }else{

                        return '<button type="button" class="btn btn-pill btn-danger-light">لا يوجد فيديو</button>';

                    }

                })
                ->editColumn('answer_pdf_file', function ($items) {
                    if ($items->answer_pdf_file){

                        return '<a href="' . asset($items->answer_pdf_file) . '">
                               رابط ملف الاجابه الورقي
                            </a>';
                    }else{

                        return '<button type="button" class="btn btn-pill btn-danger-light">لا يوجد ملف اجابه ورقي</button>';

                    }

                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.guides.parts.item', compact('id'));
        }
    }


    public function addItem($id)
    {

        $guide = Guide::query()
            ->where('id','=',$id)
            ->first();


        $subjects = SubjectClass::query()
            ->whereHas('term',fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', $guide->season_id))
            ->where('season_id', '=', $guide->season_id)
            ->select('id','name_ar')
            ->get();

        return view('admin.guides.parts.add-item', compact('subjects', 'id'));
    }



    public function addItems(AddItemStoreRequest $request): JsonResponse
    {
        $inputs = $request->all();
        if($request->hasFile('file')){
            $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/guides/file', 'photo');
        }

        if($request->hasFile('answer_pdf_file')){
            $inputs['answer_pdf_file'] = $this->saveImage($request->answer_pdf_file, 'assets/uploads/guides/answers', 'answer_pdf_file');
        }

        if($request->hasFile('answer_video_file')){
            $inputs['answer_video_file'] = $this->saveImage($request->answer_video_file, 'assets/uploads/guides/answers', 'answer_video_file');
        }



        if (Guide::create($inputs)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function editItem($id)
    {
        $guide = Guide::with(['subjectClass','lesson'])->find($id);

        $checkParentGuide = Guide::query()
            ->where('id','=',$guide->from_id)
            ->first();

        $subjects = SubjectClass::query()
            ->whereHas('term',fn (Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id', '=', $checkParentGuide->season_id))
            ->where('season_id', '=', $checkParentGuide->season_id)
            ->select('id','name_ar')
            ->get();

        return view('admin.guides.parts.update-item', compact('subjects', 'guide'));
    }



    public function updateItem(UpdateItemRequest $request, $id): JsonResponse
    {
        $items = Guide::find($id);
        $inputs = $request->all();

        if($request->hasFile('file')){
            $inputs['file'] = $this->saveImage($request->file, 'assets/uploads/guides/file', 'file');
        }

        if($request->hasFile('answer_pdf_file')){
            $inputs['answer_pdf_file'] = $this->saveImage($request->answer_pdf_file, 'assets/uploads/guides/answers', 'file');
        }

        if($request->hasFile('answer_video_file')){
            $inputs['answer_video_file'] = $this->saveImage($request->answer_video_file, 'assets/uploads/guides/answers', 'file');
        }

        if ($items->update($inputs)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



}
