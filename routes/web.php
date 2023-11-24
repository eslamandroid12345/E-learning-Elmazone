<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\VideoPartController;
use App\Http\Controllers\Api\Payment;
use App\Models\Lesson;
use App\Models\SubjectClass;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware('auth:admin')->group(function (){
    Route::get('terms/season/{id}',[TermController::class,'getAllTermsBySeason']);
    Route::get('getAllSubjectClassesBySeasonAndTerm',[VideoPartController::class,'getAllSubjectClassesBySeasonAndTerm']);
    Route::get('getAllLessonsBySubjectClass', [VideoPartController::class,'getAllLessonsBySubjectClass']);
    Route::get('getAllLessonsBySubjectClass', [VideoPartController::class,'getAllLessonsBySubjectClass']);
    Route::get('getAllStudentsBySeasonId', [NotificationController::class,'getAllStudentsBySeasonId'])->name('getAllStudentsBySeasonId');



});


// Route::get('explode', function (){

//     //list of months
//     $string = "1,2,3,4,5,6,7,8,9";

//     $explode =  explode(",",$string);

//     return in_array(7,$explode);
// });


// Route::get('update-group-months', function (){
//
//     //list of months
//
//     $users = User::query()
//     ->select('id','date_start_code','date_end_code','subscription_months_groups')
//         ->where('date_start_code','=',null)
//         ->where('date_end_code','=',null)
//     ->get();
//
//     foreach ($users as $user){
//
//         $list = [];
//
//         $period = CarbonPeriod::create( $user->date_start_code, '1 month',$user->date_end_code);
//
//         foreach ($period as $dt) {
//             $list[] = $dt->format("m");
//         }
//
//         $user->update(['subscription_months_groups' => json_encode($list)]);
//
//     }
//
//     return "All Users Done Updated";
//
// });


// Route::get('check-group-months', function (){

//     //list of months

//     $user = User::query()
//        ->where('id','=',24)
//         ->first();


//     return $user->subscription_months_groups;

// });


/*
 * Explode (String to Array)
 * Implode (Array to string)
 * explode(",",$string)
 * $period = CarbonPeriod::create( $studentAuth->date_start_code, '1 month',$studentAuth->date_end_code);

 */


