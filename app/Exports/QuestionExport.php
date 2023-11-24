<?php

namespace App\Exports;

use App\Models\Question;
use App\Models\User;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;


class QuestionExport implements FromCollection , WithHeadings ,WithColumnWidths

{

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 120,
            'C' => 15,
            'D' => 10,
            'E' => 70,
            'F' => 10,
            'G' => 10,
            'H' => 10,
        ];
    }
    public function headings(): array
    {
        return [
            'Id',
            'Question',
            'QuestionType(choice,text)',
            'Degree',
            'Type(subject_class,lesson,video,all_exam,life_exam)',
            'Difficulty (low,mid,high)',
            'Season',
            'Term',
        ];
    }
    /**
     * @return Collection
     */

    public function collection(): Collection

    {

        return Question::get(['id','question','question_type','degree','type','difficulty','season_id','term_id']);

    }

}
