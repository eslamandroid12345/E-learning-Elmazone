<?php

namespace App\Exports;

use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsExport implements FromCollection , WithHeadings ,WithColumnWidths
{
    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 50,
            'J' => 20,
            'K' => 10,

        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'birth_date',
            'phone',
            'father_phone',
            'center',
            'user_status',
            'code',
            'subscription_months_groups',
            'season_id',
            'country_id'
        ];
    }

    public function collection(): Collection{

        return User::get([
            'name',
            'birth_date',
            'phone',
            'father_phone',
            'center',
            'user_status',
            'code',
            'subscription_months_groups',
            'season_id',
            'country_id'
            ]);
    }


//    public function map($client): array
//    {
//        //dd(Date::dateTimeToExcel($client->date));
//        return [
//            $client->name,
//            Date::dateTimeToExcel($client->birth_date),
//            $client->phone,
//            $client->father_phone,
//            $client->center,
//            $client->user_status,
//            $client->code,
//            Date::dateTimeToExcel($client->date_start_code),
//            Date::dateTimeToExcel($client->date_end_code),
//            $client->season_id,
//            $client->country_id
//        ];
//    }

}
