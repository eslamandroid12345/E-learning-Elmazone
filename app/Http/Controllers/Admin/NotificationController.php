<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Traits\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationStoreRequest;
use App\Http\Requests\StoreNotification;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\Season;
use App\Models\Term;
use App\Traits\AdminLogs;
use App\Traits\PhotoTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;

class NotificationController extends Controller
{
    use FirebaseNotification, PhotoTrait, AdminLogs;

    public function index(request $request)
    {
        if ($request->ajax()) {
            $notifications = Notification::get();
            return Datatables::of($notifications)
                ->addColumn('action', function ($notifications) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $notifications->id . '" data-title="' . $notifications->name_en . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('image', function ($notifications) {

                    if ($notifications->image == null) {

                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('assets/uploads/notification/default/default.jpg') . '">
                    ';
                    } else {

                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset($notifications->image) . '">
                    ';
                    }
                })

                ->editColumn('user_type', function ($notifications) {

                    if ($notifications->user_type == "student") {

                        return '<button type="button" class="btn btn-pill btn-danger-light">اشعار لطالب</button>';

                    }elseif ($notifications->user_type == "group_of_students"){

                        return '<button type="button" class="btn btn-pill btn-danger-light">اشعار لمجموعه طلبه</button>';


                    } else {

                        return '<button type="button" class="btn btn-pill btn-danger-light">اشعار لجميع الطلبه</button>';

                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('admin.notifications.index');
    }


    public function getAllStudentsBySeasonId(): array
    {

        return User::query()
            ->where('season_id','=',request()->season_id)
            ->pluck('code', 'id')
            ->toArray();
    }


    public function create()
    {
        $data['terms'] = Term::get();
        $data['seasons'] = Season::get();
        return view('admin.notifications.parts.create', $data);
    }


    public function store(NotificationStoreRequest $request): JsonResponse
    {
        $inputs = $request->all();
        $inputs['image'] = null;
        if ($request->has('image')) {
            $inputs['image'] = $this->saveImage($request->image, 'assets/uploads/notification');
        }

        $notification = [
            'title' => $inputs['title'],
            'body' => $inputs['body'],
            'user_type' => $inputs['type'],
            'image' => $inputs['image'],
        ];


        if($request->type == 'group_of_students'){

            $notification['group_ids'] = json_encode($inputs['group_ids']);
            $this->sendFirebaseNotification(['title' => $inputs['title'], 'body' => $inputs['body']],$inputs['season_id'], null, $inputs['group_ids'], true);
            $message = "تم ارسال اشعار لطلبه محددين من قبل المدرس";

        }elseif ($request->type == 'student'){
            $notification['user_id'] = $inputs['user_id'];
             $this->sendFirebaseNotification(['title' => $inputs['title'], 'body' => $inputs['body']],null, $inputs['user_id'], null, true);
            $message = "تم ارسال اشعار لطالب معين";

        }else{

            $notification['season_id'] = $inputs['season_id'];
            $this->sendFirebaseNotification(['title' => $inputs['title'], 'body' => $inputs['body']],$inputs['season_id'],null, null , true);
            $message = "تم ارسال اشعار لجميع الطلبه";

        }

        $notificationStore = Notification::create($notification);


        if ($notificationStore->save()) {
            $this->adminLog($message);

            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }



    public function destroy(Request $request): JsonResponse
    {
        $notifications = Notification::query()
        ->where('id', $request->id)
            ->firstOrFail();

        $notifications->delete();
        $this->adminLog('تم حذف اشعار');
        return response()->json(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }

}
