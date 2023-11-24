<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoFileUploadRequest;
use App\Http\Requests\StoreVideoPart;
use App\Models\Comment;
use App\Models\CommentReplay;
use App\Models\SubjectClass;
use App\Models\Term;
use App\Models\VideoFilesUploads;
use App\Models\VideoParts;
use App\Models\Lesson;
use App\Models\VideoRate;
use App\Models\Report;
use App\Models\VideoTotalView;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Omnipay\Common\Item;
use Yajra\DataTables\DataTables;


class VideoPartController extends Controller
{
    use FirebaseNotification , PhotoTrait , AdminLogs;


    public function index(request $request)
    {
        if ($request->ajax()) {
            $videoParts = VideoParts::get();

            return Datatables::of($videoParts)
                ->addColumn('action', function ($videoParts) {
                    return '
                            <button type="button" data-id="' . $videoParts->id . '" class="btn btn-pill btn-info-light editBtn">تعديل</button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $videoParts->id . '" data-title="' . ' ' . $videoParts->name_ar . ' ' . '">
                                    حذف
                            </button>
                             <button type="button" data-id="' . $videoParts->id . '" class="btn btn-pill btn-info-light addFile"><i class="fa fa-file"></i>الملحقات ' . $videoParts->videoFileUpload->count() . ' </button>
                            <a href="' . route('indexCommentVideo', $videoParts->id) . '" data-id="' . $videoParts->id . '" class="btn btn-pill btn-success-light"> تعليقات ' . $videoParts->comment->count() . ' <i class="fa fa-comment"></i></a>
                            <a href="' . route('reportPart', $videoParts->id) . '" data-id="' . $videoParts->id . '" class="btn btn-pill btn-danger-light"> بلاغات ' . $videoParts->report->count() . ' <i class="fe fe-book"></i></a>
                       ';
                })
                ->editColumn('lesson_id', function ($videoParts) {
                    return $videoParts->lesson->name_ar;
                })
                ->addColumn('rate', function ($videoParts) {
                    $like = VideoRate::where('video_id', $videoParts->id)
                        ->where('action', '=', 'like')
                        ->count('action');

                    return $like . ' <i class="fa fa-thumbs-up ml-2 mr-2 text-success"></i>
                                    <input class="tgl tgl-ios like_active" data-id="'. $videoParts->id .'" name="like_active" id="like-' . $videoParts->id . '" type="checkbox" '. ($videoParts->like_active == 1 ? 'checked' : 'unchecked') .'/>
                                    <label class="tgl-btn" dir="ltr" for="like-' . $videoParts->id . '"></label>';
                })
                ->addColumn('view', function ($videoParts) {
                    $view = VideoTotalView::where('video_part_id', $videoParts->id)->count('count');
                    return $view . ' <i class="fa fa-eye"></i>
                                    <input class="tgl tgl-ios viewActive" data-id="'. $videoParts->id .'" '. ($videoParts->view_active == 1 ? 'checked' : 'unchecked') .' name="view_active" id="view-' . $videoParts->id . '" type="checkbox"/>
                                    <label class="tgl-btn" dir="ltr" for="view-' . $videoParts->id . '"></label>';
                })
                ->editColumn('link', function ($videoParts) {

                    if($videoParts->is_youtube == true){

                        return '<a target="_blank" href="' . $videoParts->youtube_link . '">
                                <span class="badge badge-secondary">لينك الفيديو</span>
                            </a>';
                    }else{

                        return '<a target="_blank" href="' . asset('videos/' . $videoParts->link) . '">
                                <span class="badge badge-secondary">لينك الفيديو</span>
                            </a>';
                    }

                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.videopart.index');
        }
    }


    public function reportPart(Request $request, $id)
    {
        $reports = Report::where('video_part_id', $id)->get();
        if ($request->ajax()) {
            return Datatables::of($reports)
                ->addColumn('action', function ($reports) {
                    return '
                    <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                    data-id="' . $reports->id . '" data-title="' . $reports->report . '">
                    <i class="fas fa-trash"></i>
            </button>
                       ';
                })
                ->editColumn('user_id', function ($reports) {

                    return '<td>' . $reports->user->name . '</td>';
                })
                ->editColumn('video_part_id', function ($reports) {
                    return '<a href="' . asset('video_files/' . $reports->video_part_id) . '">'
                        . $reports->video_part->name_ar .
                        '</a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.videopart.parts.report', compact('id'));
        }
    }

    public function indexCommentVideo(Request $request, $id)
    {
        if ($request->ajax()) {
            $comments = Comment::where('video_part_id', $id)->get();
            return Datatables::of($comments)
                ->addColumn('action', function ($comments) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $comments->id . '" data-title="' . $comments->comment . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                                <button type="button" data-id="' . $comments->id . '" class="btn btn-pill btn-primary-light addReply"><i class="fa fa-plus"></i>اضافة رد</button>
                            <a href="' . route('indexCommentVideoReply', $comments->id) . '" class="btn btn-pill btn-success-light">الردود<li class="fa fa-reply"></li></a>
                       ';
                })
                ->editColumn('user_id', function ($comments) {
                    return '<td>' . $comments->user->name . '</td>';
                })
                ->editColumn('image', function ($comments) {
                    if ($comments->image)
                        return '<a href="' . asset('comments_upload_file/' . $comments->image) . '">
                                ' . $comments->image . '
                            </a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.videopart.parts.comments', compact('id'));
        }
    }

    public function indexCommentVideoCreate($id)
    {
        return view('admin.videopart.parts.store_comment', compact('id'));
    }


    public function storeReplyVideo(Request $request)
    {
        $parentComment = Comment::find($request->id);
        if (!$parentComment) {
            return redirect()->back()->with('error', 'Parent comment not found.');
        }

        $reply = new CommentReplay();
        $reply->comment = $request->comment;
        $reply->comment_id = $request->id;
        $reply->user_type = 'teacher';
        $reply->teacher_id = auth('admin')->user()->id;

        $reply->save();
        $this->adminLog('تم اضافة رد علي تعليق');

        return response()->json(['status' => 200]);
    }



    public function indexCommentVideoReply(Request $request, $id)
    {
        if ($request->ajax()) {
            $comments_replys = CommentReplay::where('comment_id', $id)
                ->get();
            return Datatables::of($comments_replys)
                ->addColumn('action', function ($comments_replys) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $comments_replys->id . '" data-title="' . $comments_replys->comment . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('teacher_id', function ($comments_replys) {
                    return '<td>' . @$comments_replys->teacher->name . '</td>';
                })
                ->editColumn('student_id', function ($comments_replys) {
                    return '<td>' . @$comments_replys->student->name . '</td>';
                })
                ->editColumn('image', function ($comments_replys) {
                    if ($comments_replys->image)
                        return '<a href="' . asset('comments_upload_file/' . $comments_replys->image) . '">
                                ' . $comments_replys->image . '
                            </a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.videopart.parts.comment_reply', compact('id'));
        }
    }


    public function deleteCommentVideoReply(Request $request): JsonResponse
    {
        $comment_reply = CommentReplay::where('id', $request->id)->firstOrFail();
        $comment_reply->delete();
        $this->adminLog('تم حذف رد علي تعليق');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function create()
    {

        $seasons = DB::table('seasons')
            ->select('id','name_ar')
            ->get();

        return view('admin.videopart.parts.create', compact('seasons'));
    }



    public function store(StoreVideoPart $request): JsonResponse
    {

        if ($backgroundImage = $request->file('background_image')) {

            $destinationPath = 'videos/images';
            $imageLink = date('YmdHis') . "." . $backgroundImage->getClientOriginalExtension();
            $backgroundImage->move($destinationPath, $imageLink);
            $request['background_image'] = "$imageLink";
        }



        if ($video = $request->file('link')) {

            $destinationPath = 'videos/';
            $videoPart= date('YmdHis') . "." . $video->getClientOriginalExtension();
            $video->move($destinationPath, $videoPart);
            $request['link'] = "$videoPart";
        }


        $videoPartCreate = VideoParts::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'background_image' =>  $imageLink,
            'month' => $request->month,
            'note' => $request->note,
            'lesson_id' => $request->lesson_id,
            'link' => $videoPart ?? null,
            'youtube_link' => $request->youtube_link,
            'is_youtube' => $request->youtube_link != null ? 1 : 0,
            'video_time' => $request->video_time,

        ]);

        if($videoPartCreate->save()){

            $this->adminLog('تم اضافة فيديو');
            return response()->json(['status' => 200]);

        }else{
            return response()->json(['status' => 405, 'message' => 'Failed to save the record']);
        }

    }


    public function edit(VideoParts $videosPart)
    {
        $lessons = Lesson::get();
        return view('admin.videopart.parts.edit', compact('videosPart', 'lessons'));
    }



    public function update(StoreVideoPart $request,$id): JsonResponse
    {

        $videPartUpdate = VideoParts::query()
            ->find($id);


        $imageLink = "";
        if ($backgroundImage = $request->file('background_image')) {

            $destinationPath = 'videos/images';
            $imageLink = date('YmdHis') . "." . $backgroundImage->getClientOriginalExtension();
            $backgroundImage->move($destinationPath, $imageLink);
            $request['background_image'] = "$imageLink";

        }


        if ($video = $request->file('link')) {

            $destinationPath = 'videos/';
            $videoPart= date('YmdHis') . "." . $video->getClientOriginalExtension();
            $video->move($destinationPath, $videoPart);
            $request['link'] = "$videoPart";


            if(file_exists(public_path('videos/'.$videPartUpdate->link))){

                unlink(public_path('videos/'.$videPartUpdate->link));
            }
        }

        $videPartUpdate->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'background_image' =>  $request->file('background_image') != null ? $imageLink : $videPartUpdate->background_image,
            'month' => $request->month,
            'note' => $request->note,
            'link' => $request->file('link') == null ? $videPartUpdate->link : $videoPart ,
            'youtube_link' => $request->youtube_link ?? null,
            'is_youtube' => $request->youtube_link != null ? 1 : 0,
            'video_time' => $request->video_time,
        ]);

        if($videPartUpdate->save()){

            $this->adminLog('تم تعديل بيانات الفيديو');
            return response()->json(['status' => 200]);

        }else{
            return response()->json(['status' => 405, 'message' => 'Failed to update']);
        }
    }



    public function destroy(Request $request): JsonResponse{

        $videoParts = VideoParts::where('id', $request->id)->firstOrFail();
        $videoParts->delete();
        $this->adminLog('تم حذف فيديو');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function updateItems(Request $request): JsonResponse
    {
        $input = $request->all();

        foreach ($input['panddingArr'] as $key => $value) {
            $key = $key + 1;
            Item::where('id', $value)->update(['status' => 0, 'order' => $key]);
        }

        foreach ($input['completeArr'] as $key => $value) {
            $key = $key + 1;
            Item::where('id', $value)->update(['status' => 1, 'order' => $key]);
        }

        return response()->json(['status' => 'success']);
    }


    public function showFiles(Request $request, $id)
    {
        $files = VideoFilesUploads::where('video_part_id', '=', $id)->get();
        return view('admin.videopart.parts.files', compact('id', 'files'));
    }

    public function modifyFiles(StoreVideoFileUploadRequest $request, $id): JsonResponse
    {

        if ($request->has('file_link')) {

            $file = $request->file('file_link');
            $file_name = $file->getClientOriginalName();

            if ($request->type == 'pdf') {
                $file->move('video_files/pdf', $file_name);
            } else {
                $file->move('video_files/audios', $file_name);
            }
        }

        VideoFilesUploads::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'background_color' => $request->background_color,
            'file_link' => $file_name,
            'file_type' => $request->type,
            'video_part_id' => $id,
        ]);

        return response()->json(['status' => 200]);
    }

    public function deleteFiles(Request $request): JsonResponse
    {
        $id = $request->id;
        VideoFilesUploads::find($id)->delete();
        return response()->json(['status' => 'تم الحذف بنجاح']);
    }

    public function likeActive(Request $request)
    {
        $like = $request->like_active;
        $video = VideoParts::findOrFail($request->id);
        $video->like_active = $like;
        $video->save();
    }

    public function viewActive(Request $request){
        $view = $request->view_active;
        $video = VideoParts::findOrFail($request->id);
        $video->view_active = $view;
        $video->save();
    }


    //====================================================== get data with table relation ========================================

    public function getAllSubjectClassesBySeasonAndTerm(): array
    {

        return SubjectClass::query()
            ->where('season_id','=',request()->season_id)
            ->where('term_id','=',request()->term_id)
            ->pluck('name_ar', 'id')
            ->toArray();
    }


    public function getAllLessonsBySubjectClass(): array
    {

        return Lesson::query()
            ->where('subject_class_id','=',request()->subject_class_id)
            ->pluck('name_ar', 'id')
            ->toArray();
    }


}
