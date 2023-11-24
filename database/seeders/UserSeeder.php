<?php

namespace Database\Seeders;

use App\Models\AllExam;
use App\Models\Country;
use App\Models\Lesson;
use App\Models\PhoneCommunication;
use App\Models\Season;
use App\Models\Setting;
use App\Models\SubjectClass;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        for ($i=1;$i<=3;$i++){
            $season = Season::create([

                'name_ar' => "الصف الثانوي ${i}",
                'name_en' => "Season {$i}",

            ]);

        }

       $country = Country::create([
           'name_ar' => 'الجيزه',
           'name_en' => 'Elgeza'

        ]);

        User::create([
            'name' => 'اسلام محمد',
            'season_id' => $season->first()->id,
            'country_id' => $country->first()->id,
            'password' =>  Hash::make('123456'),
            'phone' => '01062933188',
            'father_phone' => '1005717155',
            'image' => 'avatar3.jpg',
            'user_status' => 'active',
            'center' => 'in',
            'code' => rand(1,3000),
            'date_start_code' => '2022-02-20',
            'date_end_code' => '2022-07-20',

        ]);


        for ($i=1;$i<=2;$i++){

           $term = Term::create([
                'name_ar' => $i . 'التيرم ',
                'name_en' => 'Term ' . $i,
                'status' => 'active',

            ]);

        }

        for ($i=1;$i<=6;$i++){

       $subject_class = SubjectClass::create([

         'name_ar' => $i . 'الفصل ',
         'name_en' => 'Class ' . $i,
         'term_id' => $term->first()->id,
         'season_id' => $season->first()->id,

        ]);

        }

        for ($i=1;$i<=6;$i++) {

            Lesson::create([

                'name_ar' => $i .'الدرس ',
                'name_en' => 'Lesson ' . $i,
                'subject_class_id' => $subject_class->first()->id,
                'note' => 'شرح تفصيلي لليزر والمقاومه',

            ]);
        }

        for ($i=1;$i<=3;$i++) {


            PhoneCommunication::create([

               'phone' => "0106293318" . $i,
               'note' => 'تواصل مع السكرتاريه',

            ]);
        }

        Setting::create([
            'facebook_link' => 'https://www.facebook.com/',
            'youtube_link' => 'https://www.youtube.com/'

        ]);

    }
}
