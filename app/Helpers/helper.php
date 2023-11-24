<?php
//check current language
use App\Models\AdminLog;
use App\Models\ExamSchedule;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

if (!function_exists('lang')) {

    function lang()
    {
        return Config::get('app.locale');

    }
}




############################## Start Helpers Authentication ###########################################
if (!function_exists('studentAuth')) {

    function studentAuth(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return auth('user-api')->user();

    }
}


if (!function_exists('getSeasonIdOfStudent')) {

    function getSeasonIdOfStudent()
    {
        return auth('user-api')->user()->season_id;

    }
}

if (!function_exists('userId')) {

    function userId()
    {
        return auth('user-api')->id();

    }
}



############################## End Helpers Authentication ###########################################



if (!function_exists('month_with_zero')) {

    function month_with_zero($number)
    {
        return ($number < 10) ? '0' . $number : $number;

    }
}

if (!function_exists('file_size')) {
    /**
     * @param $filePath
     * @return string
     */
    function file_size($filePath): string
    {
//        $getID3 = new \getID3;
//        $file = $getID3->analyze($filePath);
//        return number_format($file['filesize'] / 1024);
        $ch = curl_init($filePath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        $data = curl_exec($ch);
        $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpResponseCode == 200) {
            return (int)round($fileSize / 1024);
        }
        return 0;
    }
}

if (!function_exists('video_duration')) {
    /**
     * @param $videoPath
     * @return string
     */
    function video_duration($videoPath): string
    {
        $getID3 = new \getID3;
        $file = $getID3->analyze($videoPath);
        return $file['playtime_string'];
    }
}


if (!function_exists('saveFile')) {

    function saveFile($folder, $file): string
    {
        $path = public_path($folder);
        $file_name = rand('1', '9999') . time() . '.' . $file->getClientOriginalExtension();
        $file->move($path, $file_name);
        return $file_name;
    }
}

if (!function_exists('getFromToFromMonthsList')) {

    function getFromToFromMonthsList($months)
    {
        $first_day = new DateTime(date('Y') . '-' . $months[0] . '-01');
        foreach ($months as $month) {

            $last_day = new DateTime(date('Y') . '-' . $month . '-01');
            $last_day->modify('last day of this month');
        }
        return [$first_day->format('Y-m-d'), $last_day->format('Y-m-d')];
    }

}
if (!function_exists('getFromToMonthsList')) {

    function getFromToMonthsList($date_from, $date_to)
    {
        $months = [];
        $result = CarbonPeriod::create($date_from, '1 month', $date_to);

        foreach ($result as $dt) {
            array_push($months, $dt->format("j"));
        }
        return $months;
    }

}



if (!function_exists('getAllSecondsFromTimes')) {

    function getAllSecondsFromTimes($times): int
    {

        $total_seconds = array_reduce($times, function($carry, $time) {
            return $carry + strtotime("1970-01-01 $time UTC") - strtotime("1970-01-01 00:00:00 UTC");
        });

        $total_time = gmdate("H:i:s", $total_seconds);


        $time_string = $total_time;
        $seconds = strtotime("1970-01-01 $time_string UTC") - strtotime("1970-01-01 00:00:00 UTC");

        return $seconds;
    }

}

if (!function_exists('progress')){
    function progress($count)
    {
        if ($count > 1000){
            return 'w-100';
        } elseif ($count > 500){
            return 'w-75';
        }elseif ($count > 200){
            return 'w-50';
        } elseif ($count > 100){
            return 'w-25';
        } elseif ($count > 50){
            return 'w-10';
        }
    }
}
