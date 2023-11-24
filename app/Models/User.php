<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     *
     */

    protected $fillable = [
        'name',
        'birth_date',
        'email',
        'password',
        'birth_date',
        'season_id',
        'group_id',
        'center',
        'country_id',
        'phone',
        'father_phone',
        'image',
        'user_status',
        'user_status_note',
        'code',
        'date_start_code',
        'date_end_code',
        'login_status',
        'access_token',
        'subscription_months_groups'
    ];



    public function season(): BelongsTo
    {

        return $this->belongsTo(Season::class, 'season_id', 'id');
    }


    public function country(): BelongsTo
    {

        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function suggestion(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'user_id', 'id');
    }

    public function onlineExam(): BelongsToMany
    {

        return $this->belongsToMany(OnlineExam::class, 'online_exam_users');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }


    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscribe::class);
    }

    public function papel_sheet_exam_degree(): HasOne
    {

        return $this->hasOne(PapelSheetExamDegree::class, 'user_id', 'id');
    }


    public function exam_degree_depends(): HasMany
    {

        return $this->hasMany(ExamDegreeDepends::class, 'user_id', 'id');
    }




    public function exam_degree_depends_user(): HasOne
    {
        return $this->hasOne(ExamDegreeDepends::class, 'user_id', 'id');
    }


    //relation user with online_exams
    public function online_exams(): BelongsToMany
    {

        return $this->belongsToMany(OnlineExam::class, 'online_exam_users', 'user_id', 'online_exam_id', 'id', 'id');

    }


    //sum total degree of online exam
    public function online_exams_total_degrees(): BelongsToMany
    {

        return $this->belongsToMany(OnlineExam::class, 'exam_degree_depends', 'user_id', 'online_exam_id', 'id', 'id');
    }



    //sum total degree of all exam
    public function all_exams_total_degrees(): BelongsToMany
    {

        return $this->belongsToMany(AllExam::class, 'exam_degree_depends', 'user_id', 'all_exam_id', 'id', 'id');

    }

    public function exams_favorites(): HasMany
    {

        return $this->hasMany(ExamsFavorite::class, 'user_id', 'id');
    }


    /*
     * start scopes
     */


//    public function scopeDayOfExamsHeroes($query)
//    {
//
//        return $query->with(['exam_degree_depends' => fn(HasMany $q) =>
//
//             $q->where('exam_depends', '=', 'yes')
//
//            ])->whereHas('exam_degree_depends', fn(Builder $builder) =>
//            $builder->where('exam_depends', '=', 'yes')
//                ->whereDay('created_at', '=', Carbon::now()->format('d'))
//                ->whereYear('created_at', '=', Carbon::now()->format('Y'))
//
//            )
//            ->whereHas('season', fn(Builder $builder) =>
//            $builder->where('season_id', '=', auth()->guard('user-api')->user()->season_id))
//            ->take(10)
//            ->withSum(
//                ['exam_degree_depends' => function ($query) {
//                    $query->where('exam_depends', '=', 'yes');
//                }], 'full_degree')
//            ->withSum(['online_exams_total_degrees'], 'degree')
//            ->withSum(['all_exams_total_degrees'], 'degree')
//            ->withCount([
//                'exam_degree_depends' =>  function($query){
//
//                    $query->where('exam_depends', '=', 'yes');
//                }])
//            ->orderBy('exam_degree_depends_sum_full_degree', 'desc')
//            ->get();
//
//    }
//
//
//    public function scopeWeekOfExamsHeroes($query)
//    {
//
//        return $query->with(['exam_degree_depends' => fn(HasMany $q) =>
//        $q->where('exam_depends', '=', 'yes')])
//            ->whereHas('exam_degree_depends', fn(Builder $builder) =>
//            $builder->where('exam_depends', '=', 'yes')
//                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
//                ->whereYear('created_at', '=', Carbon::now()->format('Y'))
//
//            )
//            ->whereHas('season', fn(Builder $builder) =>
//            $builder->where('season_id', '=', auth()->guard('user-api')->user()->season_id))
//            ->take(10)
//            ->withSum(
//                ['exam_degree_depends' => function ($query) {
//                    $query->where('exam_depends', '=', 'yes');
//                }], 'full_degree')
//            ->withSum(['online_exams_total_degrees'], 'degree')
//            ->withSum(['all_exams_total_degrees'], 'degree')
//            ->withCount([
//
//                'exam_degree_depends' =>  function($query){
//
//                $query->where('exam_depends', '=', 'yes');
//           } ])
//            ->orderBy('exam_degree_depends_sum_full_degree', 'desc')
//            ->get();
//
//    }
//
//
//    public function scopeMonthOfExamsHeroes($query)
//    {
//
//        return $query->with(['exam_degree_depends' => fn(HasMany $q) =>
//        $q->where('exam_depends', '=', 'yes')])
//            ->whereHas('exam_degree_depends', fn(Builder $builder) =>
//            $builder->where('exam_depends', '=', 'yes')
//                ->whereMonth('created_at', Carbon::now()->format('m'))
//                ->whereYear('created_at', '=', Carbon::now()->format('Y'))
//
//            )
//            ->whereHas('season', fn(Builder $builder) =>
//            $builder->where('season_id', '=', auth()->guard('user-api')->user()->season_id))
//            ->take(10)
//            ->withSum(
//                ['exam_degree_depends' => function ($query) {
//                    $query->where('exam_depends', '=', 'yes');
//                }], 'full_degree')
//            ->withSum(['online_exams_total_degrees'], 'degree')
//            ->withSum(['all_exams_total_degrees'], 'degree')
//            ->withCount([
//
//                'exam_degree_depends' =>  function($query){
//
//                    $query->where('exam_depends', '=', 'yes');
//                } ])
//
//            ->orderBy('exam_degree_depends_sum_full_degree', 'desc')
//            ->get();
//
//    }
//
//
//    public function scopeLastMonthOfExamsHeroes($query)
//    {
//
//        return $query->with(['exam_degree_depends' => fn(HasMany $q) =>
//        $q->where('exam_depends', '=', 'yes')])
//            ->whereHas('exam_degree_depends', fn(Builder $builder) =>
//            $builder->where('exam_depends', '=', 'yes')
//                ->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))
//                ->whereYear('created_at', '=', Carbon::now()->format('Y'))
//
//            )
//            ->whereHas('season', fn(Builder $builder) =>
//            $builder->where('season_id', '=', auth()->guard('user-api')->user()->season_id))
//            ->take(10)
//            ->withSum(
//                ['exam_degree_depends' => function ($query) {
//                    $query->where('exam_depends', '=', 'yes');
//                }], 'full_degree')
//            ->withSum(['online_exams_total_degrees'], 'degree')
//            ->withSum(['all_exams_total_degrees'], 'degree')
//            ->withCount([
//
//                'exam_degree_depends' =>  function($query){
//
//                    $query->where('exam_depends', '=', 'yes');
//                } ])
//
//            ->orderBy('exam_degree_depends_sum_full_degree', 'desc')
//            ->get();
//    }



    public function onlineExams(): BelongsToMany
    {

        return $this->belongsToMany(OnlineExam::class, 'exam_degree_depends', 'user_id', 'online_exam_id', 'id', 'id')->where('exam_depends','=','yes');
    }


    public function allExams(): BelongsToMany
    {

        return $this->belongsToMany(AllExam::class, 'exam_degree_depends', 'user_id', 'all_exam_id', 'id', 'id')->where('exam_depends','=','yes');

    }



}
