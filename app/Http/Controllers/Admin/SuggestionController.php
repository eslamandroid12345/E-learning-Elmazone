<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use App\Traits\AdminLogs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SuggestionController extends Controller
{
    use AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $suggestions = Suggestion::get();
            return Datatables::of($suggestions)
                ->addColumn('action', function ($suggestions) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $suggestions->id . '" data-title="' . $suggestions->title . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->addColumn('country_id', function ($suggestions) {
                  return $suggestions->user->country->name_ar;
                })
                ->addColumn('code', function ($suggestions) {
                    return $suggestions->user->code;
                })
                ->editColumn('user_id', function ($suggestions) {
                        return '<td>'. $suggestions->user->name .'</td>';
                })
                ->editColumn('suggestion', function ($suggestions) {
                    if($suggestions->audio) {
                        return '<td>'. ($suggestions->suggestion ? $suggestions->suggestion : '____') .'</td>';
                    } else
                    {
                        return '____';
                    }
                })
                ->editColumn('audio', function ($suggestions) {
                    if ($suggestions->audio)
                        return '<a class="badge badge-danger" href="' . asset('suggestions_uploads/audios/'.$suggestions->audio) . '">
                                '. ($suggestions->audio ? 'لينك الصوت' : '____') .'
                            </a>';
                    else {
                        return '____';
                    }
                })
                ->editColumn('image', function ($suggestions) {
                    if ($suggestions->image)
                        return '<a class="badge badge-secondary" href="' . asset('suggestions_uploads/images/'.$suggestions->image) . '">
                                '. ($suggestions->image ? 'لينك الصورة' : '____') .'
                            </a>';
                    else {
                        return '____';
                    }
                })
                ->editColumn('suggestion', function ($suggestions) {
                    if ($suggestions->suggestion)
                        return '<a class="badge badge-success" href="#">
                                '. ($suggestions->suggestion ? $suggestions->suggestion : '____') .'
                            </a>';
                    else
                    {
                        return '____';
                    }
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.suggestions.index');
        }
    }


    public function destroy(Request $request)
    {
        $suggestion = Suggestion::where('id', $request->id)->firstOrFail();
        $suggestion->delete();
        $this->adminLog('تم حذف اقتراح');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


}
