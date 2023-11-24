<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeason;
use App\Models\Comment;
use App\Models\CommentReplay;
use App\Models\Lesson;
use App\Models\Season;
use App\Models\SubjectClass;
use App\Models\Term;
use App\Models\VideoParts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SeasonController extends Controller
{


    public function index(request $request)
    {
        if ($request->ajax()) {
            $seasons = Season::get();
            return Datatables::of($seasons)
                ->addColumn('action', function ($seasons) {
                    return '
                            <button type="button" data-id="' . $seasons->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $seasons->id . '" data-title="' . $seasons->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.seasons.index');
        }
    }



    public function create()
    {
        return view('admin.seasons.parts.create');
    }


    public function store(Request $request): JsonResponse
    {
        $inputs = $request->all();
        if (Season::create($inputs)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit(Season $season)
    {
        return view('admin.seasons.parts.edit', compact('season'));
    }


    public function update(Request $request, Season $season)
    {
        if ($season->update($request->all())) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request)
    {
        $seasons = Season::where('id', $request->id)->firstOrFail();
        $seasons->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function seasonTerm(Request $request)
    {
        if ($request->ajax()) {
            $terms = Term::where('season_id', $request->id)->get();
            return Datatables::of($terms)
                ->addColumn('action', function ($terms) {
                    return '
                            <button type="button" data-id="' . $terms->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $terms->id . '" data-title="' . $terms->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                            <a class="btn btn-pill btn-success-light questionBtn" data-id="' . $terms->seasons->id . '" data-target="#question_modal" href="' . route('termSubjectClass', $terms->seasons->id) . '"><i class="fa fa-book-reader"></i></a>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $id = $request->id;
            return view('admin.seasons.season_term',compact('id'));
        }
    }


    public function seasonTermCreate($id)
    {
        return view('admin.seasons.season_term.create', compact('id'));
    }



    public function seasonTermStore(Request $request, $id): JsonResponse
    {
        $inputs = $request->all();
        if (Term::create($inputs)->where('season_id', $id)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function seasonTermEdit($id)
    {
        $term = Term::where('id', $id)->first();
        return view('admin.seasons.season_term.edit', compact('id', 'term'));
    }



    public function seasonTermUpdate(Request $request,Term $term): JsonResponse
    {

        if ($term->update($request->all())) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function seasonTermDelete(Request $request)
    {
        $terms = Term::where('id', $request->id)->firstOrFail();
        $terms->delete();
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }



    public function termSubjectClass(Request $request)
    {
        if ($request->ajax()) {
            $subjectClass = SubjectClass::where('season_id', $request->id)->get();
            return Datatables::of($subjectClass)
                ->addColumn('action', function ($subjectClass) {
                    return '
                            <button type="button" data-id="' . $subjectClass->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $subjectClass->id . '" data-title="' . $subjectClass->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                            <a class="btn btn-pill btn-success-light questionBtn" data-id="' . $subjectClass->id . '" data-target="#question_modal" href="' . route('subjectClassLesson', $subjectClass->id) . '"><i class="fe fe-book"></i></a>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $id = $request->id;
            return view('admin.seasons.term_subjectClass',compact('id'));
        }
    }


    public function termSubjectClassCreate($id)
    {
        $data['terms'] = Term::where('id', $id)->first();
        return view('admin.seasons.term_subject.create', compact('id', 'data'));
    }



    public function termSubjectClassStore(Request $request, $id): JsonResponse
    {
        $inputs = $request->all();
        if (SubjectClass::create($inputs)->where('term_id', $id)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function subjectClassLesson(Request $request)
    {
        if ($request->ajax()) {
            $lessons = Lesson::where('subject_class_id', $request->id)->get();
            return Datatables::of($lessons)
                ->addColumn('action', function ($lessons) {
                    return '
                            <button type="button" data-id="' . $lessons->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $lessons->id . '" data-title="' . $lessons->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                            <a class="btn btn-pill btn-success-light questionBtn" data-id="' . $lessons->id . '" data-target="#question_modal" href="' . route('lessonVideoPart', $lessons->id) . '"><i class="icon icon-control-play"></i></a>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $id = $request->id;
            return view('admin.seasons.subjectClass_lesson',compact('id'));
        }
    }

    public function lessonVideoPart(Request $request)
    {
        if ($request->ajax()) {
            $videoParts = VideoParts::where('lesson_id', $request->id)->get();
            return Datatables::of($videoParts)
                ->addColumn('action', function ($videoParts) {
                    return '
                            <button type="button" data-id="' . $videoParts->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $videoParts->id . '" data-title="' . $videoParts->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                            <a class="btn btn-pill btn-success-light questionBtn" data-id="' . $videoParts->id . '" data-target="#question_modal" href="' . route('videoPartComment', $videoParts->id) . '"><i class="fa fa-comment"></i></a>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $id = $request->id;
            return view('admin.seasons.lessonVideoParts',compact('id'));
        }
    }


    public function videoPartComment(Request $request)
    {
        if ($request->ajax()) {
            $comments = Comment::query()
            ->where('video_part_id', $request->id)
                ->get();

            return Datatables::of($comments)
                ->addColumn('action', function ($comments) {
                    return '
                            <button type="button" data-id="' . $comments->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $comments->id . '" data-title="' . $comments->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                            <a class="btn btn-pill btn-success-light questionBtn" data-id="' . $comments->id . '" data-target="#question_modal" href="' . route('commentReplayComment', $comments->id) . '"><i class="fa fa-comments"></i></a>
                       ';
                })
                ->editColumn('user_id', function ($comments) {
                    return '<td>'. $comments->user->name .'</td>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $id = $request->id;
            return view('admin.seasons.video_part_comments',compact('id'));
        }
    }


    public function commentReplayComment(Request $request)
    {
        if ($request->ajax()) {
            $replys = CommentReplay::where('comment_id', $request->id)->get();
            return Datatables::of($replys)
                ->addColumn('action', function ($replys) {
                    return '
                            <button type="button" data-id="' . $replys->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $replys->id . '" data-title="' . $replys->comment . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $id = $request->id;
            return view('admin.seasons.comment_reply_comment',compact('id'));
        }
    }


}
