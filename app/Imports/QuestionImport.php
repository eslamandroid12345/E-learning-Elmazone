<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToCollection, WithHeadingRow
{



    public function collection(Collection $collection)
    {

//        dd($collection);
        for ($i = 0; $i < count($collection); $i++) {

            Question::query()
                ->updateOrCreate([
                    'id' => $collection[$i]['id'],
                ], [
                    'question' => $collection[$i]['question'],
                    'difficulty' => $collection[$i]['difficulty_lowmidhigh'],
                    'type' => $collection[$i]['typesubject_classlessonvideoall_examlife_exam'],
                    'question_type' => $collection[$i]['questiontypechoicetext'],
                    'degree' => $collection[$i]['degree'],
                    'season_id' => $collection[$i]['season'],
                    'term_id' => $collection[$i]['term'],
                ]);
        }
    }

}
