<?php

namespace Database\Seeders;

use App\Models\AllExam;
use App\Models\ExamInstruction;
use Illuminate\Database\Seeder;

class ExamInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ExamInstruction::create([
            'instruction' => '1- بامكان الطالب الضغط على تاجيل السؤال
            ليظهر بلون مختلف ويمكن الرجوع للحل مرة
            اخرى .',
            'trying_number' => 2,
            'number_of_question' => 3,
            'quiz_minute' => 15,
            'examable_type' => 'App\Models\AllExam',
            'examable_id' => AllExam::first()->id,
        ]);
    }
}
