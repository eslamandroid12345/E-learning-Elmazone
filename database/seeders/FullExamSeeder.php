<?php

namespace Database\Seeders;

use App\Models\AllExam;
use App\Models\Season;
use App\Models\Term;
use Illuminate\Database\Seeder;

class FullExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1;$i<=8;$i++) {

            $allExam = AllExam::create([

                'name_ar' => $i .'امتحان شامل علي الفصل ',
                'name_en' => 'Full Exam about ' . $i,
                'note' => 'يرجي الالتزام بالتعليمات والارشادات للامتحانات الشامله',
                'season_id' => Season::first()->id,
                'term_id' => Term::first()->id,

            ]);

        }
    }
}
