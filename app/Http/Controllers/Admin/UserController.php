<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Term;
use App\Models\User;
use App\Models\Season;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Country;
use App\Models\Subscribe;
use App\Traits\AdminLogs;
use App\Models\OpenLesson;
use App\Traits\PhotoTrait;
use App\Models\VideoOpened;
use Illuminate\Http\Request;
use App\Models\UserSubscribe;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Http\Requests\StoreUser;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Yajra\DataTables\DataTables;
use App\Models\ExamDegreeDepends;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PapelSheetExamDegree;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UserUpdateRequest;


class UserController extends Controller
{
    use PhotoTrait, AdminLogs;

    public function index(request $request)
    {


        if ($request->ajax()) {

            $users = User::query()
                ->select('*')
                ->when($request->season,function ($q) use($request){
                    $q->where('season_id',$request->season);
                })
                ->get();

            return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    return '
                            <button type="button" data-id="' . $users->id . '" class="btn btn-pill btn-info-light editBtn">تعديل</button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $users->id . '" data-title="' . $users->name . '">
                                    حذف
                            </button>
                            <button type="button" data-id="' . $users->id . '" class="btn btn-pill btn-success-light renew">تجديد الاشتراك</i></button>
                            <a href="' . route('printReport', $users->id) . '" data-id="' . $users->id . '" class="btn btn-pill btn-info-light reportPrint"> تقرير الطالب <i class="fa fa-file-excel"></i></a>
                       ';
                })

                ->editColumn('image', function ($users) {

                    if ($users->image == null) {

                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('default/avatar2.jfif') . '">
                    ';
                    } else {

                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset($users->image) . '">
                    ';
                    }
                })

                ->editColumn('season_id', function ($users) {
                    return $users->season->name_ar;
                })

                ->editColumn('father_phone', function ($users) {
                    if ($users->father_phone != null) {

                        return $users->father_phone;
                    } else {

                        return '<button type="button" class="btn btn-pill btn-danger-light">لا يوجد هاتف لولي امر هذا الطالب</button>';
                    }
                })

                ->editColumn('country_id', function ($users) {
                    return $users->country->name_ar;
                })

                ->editColumn('login_status', function ($users) {

                    if ($users->login_status == 1) {
                        return '<button type="button" class="btn btn-pill btn-info-light">داخل التطبيق</button>';
                    } else {

                        return '<button type="button" class="btn btn-pill btn-danger-light">غير نشط</button>';
                    }
                })

                ->editColumn('center', function ($users) {

                    if ($users->center == 'in') {
                        return '<button type="button" class="btn btn-pill btn-info-light">داخل</button>';
                    } else {

                        return '<button type="button" class="btn btn-pill btn-danger-light">خارج</button>';
                    }
                })

                ->editColumn('user_status', function ($users) {

                    if ($users->user_status == 'active') {
                        return '<button type="button" class="btn btn-pill btn-info-light">مفعل</button>';
                    } else {

                        return '<button type="button" class="btn btn-pill btn-danger-light">محظور</button>';
                    }
                })

                ->editColumn('user_status_note', function ($users) {

                    if($users->user_status_note != null)
                    {
                        return $users->user_status_note;

                    }else{
                        return '<button type="button" class="btn btn-pill btn-danger-light">لا يوجد ملاحظات عن هذا الطالب</button>';
                    }

                })
                ->escapeColumns([])
                ->make(true);
        } else {

            $seasons = Season::query()
                ->select('id','name_ar')
                ->get();

            return view('admin.users.index',compact('seasons'));
        }
    }

    public function create()
    {
        $data['seasons'] = Season::get();
        $data['countries'] = Country::get();
        $data['groupOfMonths'] = array(
            1 => 'January',
            2 => 'February',
            3 =>'March',
            4 =>'April',
            5 =>'May',
            6 => 'June',
            7 =>'July',
            8 =>'August',
            9 => 'September',
            10 =>'October',
            11 => 'November',
            12 => 'December',
        );
        return view('admin.users.parts.create')->with($data);
    }


    public function store(StoreUser $request): JsonResponse
    {

        $inputs = $request->all();
        $inputs['user_status'] = 'active';
        $inputs['password'] = Hash::make('123456');
        $inputs['subscription_months_groups'] = json_encode($request->subscription_months_groups);


//        return  $inputs;
        if ($request->has('image')) {
            $inputs['image'] = $this->saveImage($request->image, 'user', 'photo');
        }
        $newUser = User::create($inputs);
        if ($newUser) {
            $this->adminLog('تم اضافة طالب جديد');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function edit(User $user)
    {
        $data['seasons'] = Season::query()
            ->select('id', 'name_ar')
            ->get();

        $data['countries'] = Country::query()
            ->select('id', 'name_ar')
            ->get();

        $data['groupOfMonths'] = array(
            1 => 'January',
            2 => 'February',
            3 =>'March',
            4 =>'April',
            5 =>'May',
            6 => 'June',
            7 =>'July',
            8 =>'August',
            9 => 'September',
            10 =>'October',
            11 => 'November',
            12 => 'December',
        );

        $data['login_status'] = [0, 1];

        $data['user_status'] = ['in', 'out'];

        $data['user_active'] = ['active', 'not_active'];


        return view('admin.users.parts.edit',compact('user'))->with($data);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {

        $inputs = $request->except('id');
        $inputs['subscription_months_groups'] = json_encode($request->subscription_months_groups);


        if ($request->has('image')) {
            if (file_exists($user->image)) {
                unlink($user->image);
            }
            $inputs['image'] = $this->saveImage($request->image, 'user', 'photo');
        }


        if ($user->update($inputs)) {

            if ($inputs['user_status'] == 'not_active' && $user->login_status == 1) {

                JWTAuth::setToken($user->access_token)->invalidate();
                $user->update(['access_token' => null, 'login_status' => 0]);
            }
            $this->adminLog('تم تحديث طالب');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function userUnvilable()
    {
        $unavailableUsers = UserSubscribe::where('updated_at', '<=', Carbon::now()->subMonth())
            ->groupBy('student_id')
            ->select('student_id', DB::raw('MAX(id) as id'), DB::raw('MAX(updated_at) as updated_at'))
            ->get();
        return view('admin.users.parts.user_unvilable', compact('unavailableUsers'));
    }


    public function subscrView(User $user)
    {
        $userSubscriptions = UserSubscribe::where('student_id', $user->id)->pluck('month')->toArray();
        $months_user = Subscribe::whereIn('month', $userSubscriptions)->get();
        $months = Subscribe::get();
        return view('admin.users.parts.subscription_renewal', compact('user', 'months', 'months_user'));
    }


    public function subscr_renew(Request $request, User $user): RedirectResponse
    {

        $user = User::findOrFail($request->id);

        $inputs = $request->all();
        $year = $inputs['year'];

        foreach ($inputs['month'] as $value) {
            $month = Subscribe::find($value);
            UserSubscribe::create([
                'student_id' => $user->id,
                'month' => $value,
                'price' => ($user->center == 'in') ? $month->price_in_center : $month->price_out_center,
                'year' => $year
            ]);
        }
        toastr('تم التجديد بنجاح');
        return redirect()->route('users.index');
    }


    public function priceMonth(Request $request): string
    {
        $user = User::find($request->id);

        $month = $request->month;
        $price_out_center = Subscribe::whereIn('month', $month)
            ->where('season_id', $user->season_id)
            ->sum('price_in_center');

        $price_in_center = Subscribe::whereIn('month', $month)
            ->where('season_id', $user->season_id)
            ->sum('price_out_center');

        $output = '';
        $output .= '<option value="' . $price_in_center . '">' . ' السعر داخل السنتر ' . $price_in_center . '</option>';
        $output .= '<option value="' . $price_out_center . '">' . ' السعر خارج السنتر ' . $price_out_center . '</option>';

        return $output;
    }


    public function printReport($id)
    {
        $user = User::findOrFail($id);
        $term = Term::where('season_id', $user->season_id)
            ->where('status', '=', 'active')->first();
        $lessonCount = OpenLesson::where('user_id', $user->id)
            ->where('lesson_id', '!=', null)->count('lesson_id');

        $classCount = OpenLesson::where('user_id', $user->id)
            ->where('subject_class_id', '!=', null)->count('subject_class_id');

        $videos = VideoOpened::where('user_id', $user->id)
            ->where('type', 'video')
            ->with('video')->get();

        $videoMin = VideoOpened::query()
            ->where('user_id', $user->id)
            ->where('type', 'video')
            ->get('minutes');

        $totalTime = Carbon::parse('00:00:00');

        foreach ($videoMin as $timeValue) {
            // Parse the time value into a Carbon instance
            $timeCarbon = Carbon::parse($timeValue->minutes);

            // Add the hours, minutes, and seconds to the total time
            $totalTime->addHours($timeCarbon->hour);
            $totalTime->addMinutes($timeCarbon->minute);
            $totalTime->addSeconds($timeCarbon->second);
        }

        $totalTimeFormatted = $totalTime->format('H:i:s');

        $exams = ExamDegreeDepends::where('user_id', '=', $user->id)
            ->where('exam_depends', '=', 'yes')->get();
        $paperExams = PapelSheetExamDegree::where('user_id', '=', $user->id)->get();
        $subscriptions = UserSubscribe::where('student_id', $user->id)->get();
        return view('admin.users.parts.report', compact([
            'user',
            'videos',
            'exams',
            'subscriptions',
            'paperExams',
            'term',
            'lessonCount',
            'classCount',
            'totalTimeFormatted'

        ]));
    }

    public function autoPrintReport($id)
    {
        $user = User::findOrFail($id);

        $term = Term::query()
            ->where('season_id', $user->season_id)
            ->where('status', '=', 'active')
            ->first();

        $lessonCount = OpenLesson::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', '!=', null)
            ->count('lesson_id');

        $classCount = OpenLesson::query()
            ->where('user_id', $user->id)
            ->where('subject_class_id', '!=', null)
            ->count('subject_class_id');

        $videos = VideoOpened::query()
            ->where('user_id', $user->id)
            ->where('type', 'video')
            ->with('video')->get();

        $videoMin = VideoOpened::query()
            ->where('user_id', $user->id)
            ->where('type', 'video')
            ->get('minutes');

        $totalTime = Carbon::parse('00:00:00');

        foreach ($videoMin as $timeValue) {
            // Parse the time value into a Carbon instance
            $timeCarbon = Carbon::parse($timeValue->minutes);

            // Add the hours, minutes, and seconds to the total time
            $totalTime->addHours($timeCarbon->hour);
            $totalTime->addMinutes($timeCarbon->minute);
            $totalTime->addSeconds($timeCarbon->second);
        }

        $totalTimeFormatted = $totalTime->format('H:i:s');

        $exams = ExamDegreeDepends::query()
            ->where('user_id', '=', $user->id)
            ->where('exam_depends', '=', 'yes')
            ->get();

        $paperExams = PapelSheetExamDegree::query()
            ->where('user_id', '=', $user->id)
            ->get();

        $subscriptions = UserSubscribe::query()
            ->where('student_id', $user->id)
            ->get();


        return view('admin.users.parts.auto_report', compact([
            'user',
            'videos',
            'exams',
            'subscriptions',
            'paperExams',
            'term',
            'lessonCount',
            'classCount',
            'totalTimeFormatted'

        ]));
    }


    public function destroy(Request $request)
    {
        $user = User::query()
            ->where('id', $request->id)
            ->firstOrFail();

        //delete token of this student when delete him
        if($user->access_token){

            JWTAuth::setToken($user->access_token)->invalidate();
        }

        $user->delete();
        $this->adminLog('تم حذف طالب ');
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }


    public function studentsExport(): BinaryFileResponse
    {
        return Excel::download(new StudentsExport(), 'Students.xlsx');
    }

    public function studentsImport(Request $request): JsonResponse
    {
        $import = Excel::import(new StudentsImport(), $request->exelFile);
        if ($import) {
            $this->adminLog('تم استيراد ملف جديد لبيانات الطلبه');
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 500]);
        }
    }
}
