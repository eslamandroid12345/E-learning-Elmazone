<?php

namespace Database\Seeders;

use App\Models\PapelSheetExam;
use App\Models\PapelSheetExamTime;
use Illuminate\Database\Seeder;

class ExamTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        for ($i = 1; $i <= 5;$i++){

            PapelSheetExamTime::create([
                'from' => '7:00:00',
                'to' => '8:00:00',
                'papel_sheet_exam_id' => PapelSheetExam::first()->id,
            ]);
        }
    }
}
