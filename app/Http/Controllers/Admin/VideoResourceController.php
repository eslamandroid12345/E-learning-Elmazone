<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoResource;
use App\Http\Requests\UpdateVideoResource;
use App\Models\Comment;
use App\Models\CommentReplay;
use App\Models\Term;
use App\Models\Season;
use App\Models\Report;
use App\Models\VideoParts;
use App\Models\VideoResource;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VideoResourceController extends Controller
{
    use FirebaseNotification,PhotoTrait , AdminLogs;

    public function index(request $request)
    {
        $video_resource_list = VideoResource::select('*');
        $terms = Term::all();
        $seasons = Season::all();
        if ($request->ajax()) {
            if ($request->has('term_id') && $request->term_id != '') {
                $term = $request->get('term_id');
                $video_resource_list->where('term_id', $term);
            }
            $video_resource = $video_resource_list->get();
            return Datatables::of($video_resource)
                ->addColumn('action', function ($video_resource) {
                    return '
                            <button type="button" data-id="' . $video_resource->id . '" class="btn btn-pill btn-info-light editBtn">تعديل</button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $video_resource->id . '" data-title="' . $video_resource->name_ar . '">
                                   حذف
                            </button>
                            <a href="' . route('indexCommentResource', $video_resource->id) . '" data-id="' . $video_resource->id . '" class="btn btn-pill btn-success-light"> تعليقات '. $video_resource->comment->count() .' <i class="fa fa-comment"></i></a>
                            <a href="' . route('ReportVideosResource', $video_resource->id) . '" data-id="' . $video_resource->id . '" class="btn btn-pill btn-danger-light"> بلاغات '. $video_resource->report->count() .' <i class="fe fe-book"></i></a>

                       ';
                })
                ->editColumn('like_active', function ($video_resource) {
                    return '<input class="tgl tgl-ios like_active" data-id="'. $video_resource->id .'" name="like_active" id="like-' . $video_resource->id . '" type="checkbox" '. ($video_resource->like_active == 1 ? 'checked' : 'unchecked') .'/>
                    <label class="tgl-btn" dir="ltr" for="like-' . $video_resource->id . '"></label>';
            })
            ->editColumn('view_active', function ($video_resource) {
                    return '<input class="tgl tgl-ios view_active" data-id="'. $video_resource->id .'" name="view_active" id="view-' . $video_resource->id . '" type="checkbox" '. ($video_resource->view_active == 1 ? 'checked' : 'unchecked') .'/>
                    <label class="tgl-btn" dir="ltr" for="view-' . $video_resource->id . '"></label>';
            })
                ->editColumn('image', function ($video_resource) {
                    return '<img style="width:60px;border-radius:30px" onclick="window.open(this.src)" src="' . asset('videos_resources/images/' . $video_resource->image) . '"/>';
                })
                ->editColumn('background_color', function ($video_resource) {
                    return '<input type="color" class="form-control" name="background_color"
                           value="' . $video_resource->background_color . '" disabled>';
                })
                ->editColumn('video_link', function ($video_resource) {
                    if ($video_resource->is_youtube == false) {
                        return '<a href="' . asset('videos_resources/videos/'.$video_resource->video_link) . '">اضغط للوصول للفيديو</a>';
                    } else {
                        return '<a href="' .$video_resource->youtube_link . '">اضغط للوصول للفيديو</a>';

                    }
                })
                ->editColumn('pdf_file', function ($video_resource) {
                    if ($video_resource->pdf_file)
                        return '<a href="' . asset('videos_resources/pdf/'.$video_resource->pdf_file) . '">اضغط للوصول للملف الورقي</a>';
                    else
                    {
                        return '____';
                    }
                })
                ->editColumn('time', function ($video_resource) {
                    if ($video_resource->time)
                        return '<td>'. ($video_resource->time ? $video_resource->time : '____') .'</td>';
                    else
                    {
                        return '____';
                    }
                })
                ->filter(function ($video_resource) use ($request) {
                    if ($request->get('season_id')) {
                        $video_resource->where('season_id', $request->get('season_id'))->get();
                    }
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.video_resource.index', compact('terms', 'seasons'));
        }
    }


    public function ReportVideosResource(Request $request, $id)
    {
        $reports = Report::where('video_resource_id', $id)->get();
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

                        return '<td>'. $reports->user->name .'</td>';
                })
                ->editColumn('video_resource_id', function ($reports) {
                    return '<a href="' . asset('videos_resources/'.$reports->video_resource_id) . '">'
                           . $reports->video_resource->name_ar .
                        '</a>';
            })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.video_resource.parts.report', compact('id'));
        }
    }


    public function indexCommentResource(Request $request,$id)
    {
        if ($request->ajax()) {
            $comments = Comment::where('video_resource_id', $id)->get();
            return Datatables::of($comments)
                ->addColumn('action', function ($comments) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $comments->id . '" data-title="' . $comments->comment . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                                <button type="button" data-id="' . $comments->id . '" class="btn btn-pill btn-primary-light addReply"><i class="fa fa-plus"></i>اضافة رد</button>
                            <a href="' . route('indexCommentResourceReply', $comments->id) . '" class="btn btn-pill btn-success-light">الردود<li class="fa fa-reply"></li></a>
                       ';
                })
                ->editColumn('user_id', function ($comments) {
                    return '<td>'. $comments->user->name .'</td>';
                })
                ->editColumn('image', function ($comments) {
                    if ($comments->image)
                        return '<a href="' . asset('comments_upload_file/'.$comments->image) . '">
                                '.$comments->image.'
                            </a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.video_resource.parts.comments',compact('id'));
        }
    }


    public function indexCommentResourceReply(Request $request,$id)
    {
        if ($request->ajax()) {
            $comments_replys = CommentReplay::where('comment_id', $id)->get();
            return Datatables::of($comments_replys)
                ->addColumn('action', function ($comments_replys) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $comments_replys->id . '" data-title="' . $comments_replys->comment . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                                <button type="button" data-id="' . $comments_replys->id . '" class="btn btn-pill btn-primary-light addReply"><i class="fa fa-plus"></i>اضافة رد</button>
                            <a href="' . route('indexCommentVideoReply', $comments_replys->id) . '" class="btn btn-pill btn-success-light">الردود<li class="fa fa-reply"></li></a>
                       ';
                })
                ->editColumn('user_id', function ($comments_replys) {
                    if($comments_replys->user->name) {
                        return '<td>'. ($comments_replys->user->name ? $comments_replys->user->name : '____') .'</td>';
                    }
                    else
                    {
                        return '____';
                    }
                })
                ->editColumn('image', function ($comments_replys) {
                    if ($comments_replys->image)
                        return '<a href="' . asset('comments_upload_file/'.$comments_replys->image) . '">
                                '.$comments_replys->image.'
                            </a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.video_resource.parts.comment_reply',compact('id'));
        }
    }

    public function indexCommentResourceCreate($id)
    {
        return view('admin.video_resource.parts.store_comment', compact('id'));
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

    // Delete comment Reply
    public function deleteCommentResourceReply(Request $request)
    {
        $comment_reply = CommentReplay::where('id', $request->id)->firstOrFail();
        $comment_reply->delete();
        $this->adminLog('تم حذف رد علي تعليق');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

    public function videoResourceSort(Request $request)
    {
        $season = $request->season_id;
        $video_resources = Term::where('season_id', $season)->get();

        $output = '<option value="">اختر الترم</option>';

        foreach ($video_resources as $video_resource) {
            $output .= '<option value="' . $video_resource->id . '">' . $video_resource->name_ar . ' </option>';
        }
        if ($video_resource->count() > 0) {
            return $output;
        } else {
            return '<option value="">لا يوجد ترمات</option>';
        }

    }



    public function create()
    {
        $data['seasons'] = Season::all();
        $data['terms'] = Term::all();
        return view('admin.video_resource.parts.create', compact('data'));
    }

    public function store(StoreVideoResource $request){

        if ($request->file('image')) {

            $imageLink = $this->saveImageInFolder($request->file('image'),'videos_resources/images');
        }


        if ($request->file('video_link')) {

            $videoResourceLink = $this->saveImageInFolder($request->file('video_link'),'videos_resources/videos');

        }


        if ($request->file('pdf_file')) {

            $pdfLink = $this->saveImageInFolder($request->file('pdf_file'), 'videos_resources/pdf');

        }

        $videoResourceCreate = VideoResource::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'image' => $imageLink ?? null,
            'pdf_file' => $pdfLink ?? null,
            'background_color' => $request->background_color,
            'time' => $request->time,
            'season_id' => $request->season_id,
            'term_id' => $request->term_id,
            'video_link' => $videoResourceLink ?? null,
            'youtube_link' => $request->youtube_link ?? null,
            'is_youtube' => $request->youtube_link != null ? 1 : 0,

        ]);

        if($videoResourceCreate->save()){
            $this->sendFirebaseNotificationWhenAddedVideo(['title' => "اشعار جديد","body" => "تم اضافه فيديو مراجعه جديد "],$videoResourceCreate->season_id,"video_resource",$videoResourceCreate->id);
            $this->adminLog('تم اضافة فيديو');
            return response()->json(['status' => 200]);

        }else{
            return response()->json(['status' => 405, 'message' => 'Failed to save the record']);
        }
    }


    public function edit(VideoResource $videoResource)
    {
        $data['seasons'] = Season::all();
        $data['terms'] = Term::all();
        return view('admin.video_resource.parts.edit', compact('videoResource', 'data'));
    }



    public function update(StoreVideoResource  $request, VideoResource $videoResource): JsonResponse
    {

        if ($request->file('image')) {

            $imageLink = $this->saveImageInFolder($request->file('image'),'videos_resources/images');
        }


        if ($request->file('video_link')) {

            $videoResourceLink = $this->saveImageInFolder($request->file('video_link'),'videos_resources/videos');

        }


        if ($request->file('pdf_file')) {

            $pdfLink = $this->saveImageInFolder($request->file('pdf_file'), 'videos_resources/pdf');

        }

        $videoResource->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'image' =>  $request->file('image') != null ? $imageLink :  $videoResource->image,
            'pdf_file' => $request->file('pdf_file') != null ? $pdfLink :   $videoResource->pdf_file,
            'background_color' => $request->background_color,
            'time' => $request->time,
            'season_id' => $request->season_id,
            'term_id' => $request->term_id,
            'video_link' => $request->file('video_link') != null ? $videoResourceLink : $videoResource->video_link,
            'youtube_link' => $request->youtube_link ?? $videoResource->youtube_link,
            'is_youtube' => $request->youtube_link != null ? 1 : 0,

        ]);

        if($videoResource->save()){

            $this->adminLog('تم تعديل فيديو الشرح');
            return response()->json(['status' => 200]);

        }else{
            return response()->json(['status' => 405, 'message' => 'Failed to save the record']);
        }
    }


    public function destroy(Request $request): JsonResponse
    {
        $terms = VideoResource::where('id', $request->id)->firstOrFail();
        $terms->delete();
        $this->adminLog('تم حذف فيديو مراجعة');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function likeActive(Request $request)
    {
        $like = $request->like_active;
        $video = VideoResource::findOrFail($request->id);
        $video->like_active = $like;
        $video->save();
    }

    public function viewActive(Request $request)
    {
        $view = $request->view_active;
        $video = VideoResource::findOrFail($request->id);
        $video->view_active = $view;
        $video->save();
    }
}
