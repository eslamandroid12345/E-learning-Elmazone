<?php

use App\Http\Controllers\Api\AboutMe\AboutMeController;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\AllExamsUsersDegreeController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Comment\CommentController;
use App\Http\Controllers\Api\Degree\DegreeController;
use App\Http\Controllers\Api\DegreeOfExamsDetails\DegreeExamsDetailsController;
use App\Http\Controllers\Api\ExamEntry\ExamEntryController;
use App\Http\Controllers\Api\ExamYourselfTest\TestYourselfExamsController;
use App\Http\Controllers\Api\Favorites\FavoriteController;
use App\Http\Controllers\Api\FullExams\FullExamController;
use App\Http\Controllers\Api\Guides\GuideController;
use App\Http\Controllers\Api\Instruction\InstructionController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LessonDetails\LessonDetailsController;
use App\Http\Controllers\Api\LifeExam\LifeExamController;
use App\Http\Controllers\Api\LiveExam\LiveExamController;
use App\Http\Controllers\Api\MonthlyPlan\MonthlyPlanController;
use App\Http\Controllers\Api\Notes\NoteController;
use App\Http\Controllers\Api\OnBoardingController;
use App\Http\Controllers\Api\PayMob\CheckoutController;
use App\Http\Controllers\Api\PayMob\PaymentController;
use App\Http\Controllers\Api\PayMob\PayMobController;
use App\Http\Controllers\Api\Report\ReportController as ReportStudentController;
use App\Http\Controllers\Api\StudentReport\ReportController;
use App\Http\Controllers\Api\SubjectClass\SubjectClassController;
use App\Http\Controllers\Api\SubscribeController;
use App\Http\Controllers\Api\VideoRate\VideoRateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'lang'], function (){


    Route::post('add-notification',[AuthController::class,'add_notification']);
    Route::get('teacher/about-me',[AboutMeController::class,'about_me'])->middleware('jwt');

    Route::group(['prefix' => 'auth'], function (){

    Route::post('login',[AuthController::class,'login']);
    Route::get('communication',[AuthController::class,'communication']);

    Route::middleware('jwt')->group(function (){
        Route::post('logout',[AuthController::class,'logout']);
        Route::get('getProfile',[AuthController::class,'getProfile']);
        Route::post('add-suggest',[AuthController::class,'addSuggest']);
        Route::post('notification-update-status/{id}',[AuthController::class,'notificationUpdateStatus']);
        Route::get('all-notifications',[AuthController::class,'allNotifications']);
        Route::post('add-device-token',[AuthController::class,'add_device_token']);
        Route::post('papel-sheet-exam/user/{id}',[AuthController::class,'paper_sheet_exam']);
        Route::get('papel-sheet-exam/show',[AuthController::class,'paper_sheet_exam_show']);
        Route::get('paper-sheet-exam/register-with-student-check-before',[AuthController::class, 'paperSheetExamForStudentDetails']);
        Route::delete('paper-sheet-exam/latest-paper-exam-delete',[AuthController::class,'latestPaperExamDelete']);
        Route::post('update-profile',[AuthController::class,'updateProfile']);
        Route::get('home-page',[AuthController::class,'home_page']);
        Route::get('home-page/all-classes',[AuthController::class,'allClasses']);
        Route::get('home-page/all-exams',[AuthController::class,'all_exams']);
        Route::get('home-page/start-your-journey',[AuthController::class,'startYourJourney']);
        Route::get('home-page/start-your-journey/findExamByClassById/{id}',[AuthController::class,'findExamByClassById']);
        Route::get('home-page/videos-resources',[AuthController::class,'videosResources']);
        Route::get('all-subscribes',[SubscribeController::class,'all']);
        Route::post('user-add-screenshot',[AuthController::class,'user_add_screenshot']);
        Route::get('invite-your-friends',[AuthController::class,'inviteYourFriends']);
        Route::get('exam-countdown',[AuthController::class,'examCountdown']);

    });
  });

    Route::group(['prefix' => 'classes','middleware' => ['jwt']], function (){
        Route::get('all',[SubjectClassController::class,'allClasses']);
        Route::get('lessonsByClassId/{id}',[SubjectClassController::class,'lessonsByClassId']);
    });

    Route::group(['prefix' => 'fullExams','middleware' => ['jwt']], function (){
        Route::get('all',[FullExamController::class,'fullExams']);
        Route::get('instructionByFullExamId/{id}',[FullExamController::class,'instructionByFullExamId']);
    });

    Route::group(['prefix' => 'lesson','middleware' => ['jwt']], function (){
        Route::get('videos/{id}',[LessonController::class,'allVideos']);
        Route::get('video/details/{id}',[LessonController::class,'videoDetails']);
        Route::get('video/comments/{id}',[LessonController::class,'videoComments']);
        Route::post('video/add-comment',[CommentController::class,'videoAddComment']);
        Route::post('comment/add-replay/{id}',[CommentController::class,'commentAddReplay']);
        Route::post('{id?}',[LessonController::class,'accessFirstVideo']);

    });

        Route::post('video-update-time/{id}',[LessonController::class,'updateMinuteVideo'])->middleware('jwt');
        Route::group(['prefix' => 'plans','middleware' => ['jwt']], function (){
            Route::get('all',[MonthlyPlanController::class,'all_plans']);

        });

        Route::group(['prefix' => 'guide','middleware' => ['jwt']], function (){
            Route::get('sources_references/all',[GuideController::class,'index']);
            Route::get('sources_references/by-lesson/{id}/{lesson_id}',[GuideController::class,'itemsByLesson']);

        });

        Route::group(['prefix' => 'video','middleware' => 'jwt'], function (){
            Route::post('comment/update/{id}',[CommentController::class,'updateComment']);
            Route::delete('comment/delete/{id}',[CommentController::class,'deleteComment']);
            Route::post('replay/update/{id}',[CommentController::class,'updateReplay']);
            Route::delete('replay/delete/{id}',[CommentController::class,'deleteReplay']);
        });

        Route::group(['prefix' => 'ExamEntry','middleware' => ['jwt']], function (){
            Route::get('all-of-questions/{id}',[ExamEntryController::class,'all_questions_by_online_exam']);
        });

        Route::group(['prefix' => 'ExamEntry','middleware' => ['jwt']], function (){
            Route::post('exam/{id}',[ExamEntryController::class,'online_exam_by_user']);
             Route::get('exam-degree-depends-with-student/{id}',[ExamEntryController::class,'degreesDependsWithStudent']);
        });


        Route::group(['prefix' => 'degrees','middleware' => ['jwt']], function (){
            Route::get('all-exams-degrees',[DegreeController::class,'degrees']);
            });

        Route::get('ads',[AdsController::class,'index'])->middleware('jwt');
        Route::get('on-boarding',[OnBoardingController::class,'index']);

        Route::middleware('jwt')->group(function (){
            Route::get('exam-degree/details',[AllExamsUsersDegreeController::class,'all_exams_details']);
            Route::get('exam-degree/heroes',[AllExamsUsersDegreeController::class,'all_exams_heroes']);
            Route::post('access-end-time/exam/{id}',[ExamEntryController::class,'access_end_time_for_exam']);

        });

        Route::middleware('jwt')->group(function (){
            Route::get('life-exam/access-first-question/{id}',[LifeExamController::class,'access_first_question']);
            Route::get('live-exam/{id}',[LifeExamController::class,'access_live_exam']);
            Route::post('life-exam/add-life-exam/{id}',[LifeExamController::class,'add_life_exam_with_student']);
            Route::post('live-exam/add-live-exam/{id}',[LifeExamController::class,'solve_live_exam_with_student']);

        });

        Route::post('access-end-time/exam/{id}',[ExamEntryController::class,'access_end_time_for_exam'])
            ->middleware('jwt');

        Route::get('reports/student-report',[ReportController::class,'student_report'])->middleware('jwt');
        Route::post('user-rate-video/{id}',[VideoRateController::class,'user_rate_video'])->middleware('jwt');

        Route::get('print/{id}', [LessonController::class, 'printReport'])->name('printReport');


        Route::group(['prefix' => 'report','middleware' => 'jwt'], function (){

            Route::post('student-add-report',[ReportStudentController::class,'studentAddReport']);
            Route::get('all-by-student',[ReportStudentController::class,'allByStudent']);
            Route::delete('delete/{id}',[ReportStudentController::class,'delete']);

        });

    Route::group(['prefix' => 'favorite','middleware' => 'jwt'], function (){

        Route::post('exam-add-favorite',[FavoriteController::class,'examAddFavorite']);
        Route::post('video-add-favorite',[FavoriteController::class,'videoAddFavorite']);
        Route::get('all',[FavoriteController::class,'favoriteAll']);
    });


    Route::get('instruction/exam/{id}',[InstructionController::class,'instructionExamDetails']);

    Route::group(['prefix' => 'lesson','middleware' => 'jwt'], function (){
        Route::get('all-video-by-lessonId/{id}',[LessonDetailsController::class,'allVideoByLessonId']);
        Route::get('all-pdf-by-videoId/{id}',[LessonDetailsController::class,'allPdfByVideoId']);
        Route::get('all-audios-by-videoId/{id}',[LessonDetailsController::class,'allAudiosByVideoId']);
        Route::get('all-exams-by-videoId/{id}',[LessonDetailsController::class,'allExamsByVideoId']);
        Route::get('all-exams-by-lessonId/{id}',[LessonDetailsController::class,'allExamsByLessonId']);
        Route::get('exam-details-by-examId/{id}',[LessonDetailsController::class,'examDetailsByExamId']);
    });

    Route::group(['prefix' => 'degree-details','middleware' => 'jwt'], function (){
        Route::get('all-exams',[DegreeExamsDetailsController::class,'allExamsDegreeDetails']);
        Route::get('class/{id}',[DegreeExamsDetailsController::class,'classDegreeDetails']);
        Route::get('videos-by-lesson/{id}',[DegreeExamsDetailsController::class,'videosByLessonDegreeDetails']);
        Route::get('lesson/{id}',[DegreeExamsDetailsController::class,'lessonDegreeDetails']);

    });

    Route::group(['prefix' => 'notes','middleware' => 'jwt'], function (){
        Route::post('note-add-by-student',[NoteController::class,'noteAddByStudent']);
        Route::get('note-all-by-date',[NoteController::class,'noteAllByDate']);
        Route::get('dates-of-notes',[NoteController::class,'datesOfNotes']);
        Route::delete('note-delete/{id}',[NoteController::class,'noteDelete']);

    });

    Route::group(['prefix' => 'test-yourself-exams','middleware' => 'jwt'], function (){
        Route::post('make-exam',[TestYourselfExamsController::class,'makeExam']);
        Route::get('exam-questions/{id}',[TestYourselfExamsController::class,'examQuestions']);
        Route::post('solve-exam/{id}',[TestYourselfExamsController::class,'solveExam']);
        Route::get('all-classes-with-lessons',[TestYourselfExamsController::class,'allClassesWithLessons']);

    });

    Route::group(['prefix' => 'live-exam','middleware' => 'jwt'], function (){
        Route::get('all-of-questions/{id}',[LiveExamController::class,'allOfQuestions']);
        Route::post('add-exam-by-student/{id}',[LiveExamController::class,'addLiveExamByStudent']);
        Route::get('heroes/{id}',[LiveExamController::class,'allOfExamHeroes']);
        Route::get('result/{id}',[LiveExamController::class,'resultOfLiveExam']);
    });

    Route::middleware('jwt')->group(function (){
        Route::get('live-exam-all',[LiveExamController::class,'allOfLiveExamsStudent']);
        Route::get('live-exam-all/choose-live-exam',[LiveExamController::class,'choose_live_exam']);
        Route::get('exam-heroes/all',[ExamEntryController::class,'examHeroesAll']);
    });

    Route::group(['prefix' => 'payments','middleware' => 'jwt'], function (){

        Route::get('all-months',[PaymentController::class,'allMonths']);
        Route::post('add-payment-by-student',[PaymentController::class,'addPaymentByStudent']);
        Route::post('check-money-paid-with-discount',[PaymentController::class,'checkMoneyPaidWithDiscount']);

    });

    Route::post('processed',[CheckoutController::class,'index'])->middleware('jwt');
    Route::post('checkout/processed',[PayMobController::class,'checkout_processed']);
    Route::get('checkout/response',[PayMobController::class,'responseStatus']);
    Route::get('checkout',[PayMobController::class,'checkout']);

});

/*
 *
 *
 *
    Card number : 4987654321098769
    Cardholder Name : Test Account
    Expiry Month : 12
    Expiry year : 25
    CVV : 123

 */

/*
 *
 * #####################################################
 *   Elhemsi
     PayMob_Username="01061994948"
     PayMob_Password="eslamemo457@gmail.com_UPER"
     PayMob_Integration_Id="4325358"
     PayMob_HMAC="9C02EF4DE4DD4EDC29CACC6D8D988CBE"

 ###########################################################

    Frame : 402378
    PayMob_Username="01062933188"
    PayMob_Password="eslamemo457@gmail.com_UPER"
    PayMob_Integration_Id="2238564"
    PayMob_HMAC="3B2AAFBF4CDCDBE3DC5F2CC3B6F529CC"

 ###########################################################
    ElmazON Paymob configration

    Frame : 758783
    PayMob_Username="01004495595"
    PayMob_Password="Hv6%#-760"
    PayMob_Integration_Id="3796395"
    PayMob_HMAC="4A977298DCE964E63A87F2A8AD8C3526"


    ##############################################################

    Frame : 803000
    PayMob_Username="01025157437"
    PayMob_Password="ESLAM_#mohamed01062933188"
    PayMob_Integration_Id="4353402"
    PayMob_HMAC="1016F4486CEC89B90ED6AC4001DAEDA8"
 */

