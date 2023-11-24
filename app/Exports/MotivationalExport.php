<?php

namespace App\Exports;

use App\Models\MotivationalSentences;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;


class MotivationalExport implements FromCollection , WithHeadings ,WithColumnWidths

{

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 80,
            'C' => 80,
            'D' => 20,
            'E' => 20,
        ];
    }
    public function headings(): array
    {
        return [
            '#',
            'Title ar',
            'Title en',
            'Percentage from',
            'Percentage to',
        ];
    }
    /**
     * @return Collection
     */

    public function collection(): Collection

    {
        return MotivationalSentences::query()->select('id','title_ar','title_en','percentage_from','percentage_to')->get();

    }

}
