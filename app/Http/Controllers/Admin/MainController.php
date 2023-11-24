<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Guide;
use App\Models\Lesson;
use App\Models\LifeExam;
use App\Models\OnlineExam;
use App\Models\PapelSheetExam;
use App\Models\Question;
use App\Models\Section;
use App\Models\SubjectClass;
use App\Models\Suggestion;
use App\Models\User;
use App\Models\VideoBasic;
use App\Models\VideoParts;
use App\Models\VideoResource;
use Illuminate\Database\Query\Builder;
use function Clue\StreamFilter\fun;

class MainController extends Controller
{
    public function index()
    {
        $data['users'] = User::count();
        $data['usersIn'] = User::where('center', '=', 'in')->count();
        $data['usersOut'] = User::where('center', '=', 'out')->count();
        $data['onlineExam'] = OnlineExam::count();
        $data['paperExam'] = PapelSheetExam::count();
        $data['liveExam'] = LifeExam::count();
        $data['videoResource'] = VideoResource::count();
        $data['videoParts'] = VideoParts::count();
        $data['videoBasic'] = VideoBasic::count();
        $data['question'] = Question::count();
        $data['lesson'] = Lesson::count();
        $data['class'] = SubjectClass::count();
        $data['suggest'] = Suggestion::count();
        $data['section'] = Section::count();
        $data['guide'] = Guide::count();

        $cities_data = User::groupBy('country_id')
            ->select('country_id', \DB::raw('count(*) as total'))
            ->with('country')
            ->with('country.city')
            ->get();

        $countStudentByCity = \DB::table('users')
            ->join('countries', 'users.country_id', '=', 'countries.id')
            ->join('cities', 'countries.city_id', '=', 'cities.id')
            ->groupBy('countries.city_id')
            ->select('cities.name_ar','countries.city_id', \DB::raw('count(*) as coun_total'))
            ->get(); // Add get() to execute the query and fetch the results

        $cities = [];
        foreach ($cities_data as $key => $cities_datumm) {
            foreach ($countStudentByCity as $countStudent){
                if ($countStudent->name_ar == $cities_datumm->country->city->name_ar){
                    $cities['city_count'][$countStudent->name_ar]= $countStudent->coun_total;
                }
                $cities['city_name'][$key] = $cities_datumm->country->city->name_ar;
                $cities['country_total'][$key] = $cities_datumm->total;
                $cities['country_name'][$key] = $cities_datumm->country->name_ar . '( '.$cities_datumm->country->city->name_ar.' )';
            }
        }

        $data['cities'] = $cities;
        $data['city_total'] = $cities['city_count'];

        return view('admin.index')->with($data);
    }


}
