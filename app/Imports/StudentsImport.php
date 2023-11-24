<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

//class StudentsImport implements ToCollection, WithHeadingRow,WithMapping
class StudentsImport implements ToCollection, WithHeadingRow
{

   public function generateUniqueCode(): string
   {

        $characters = rand(50000,100000000).uniqid();
        $charactersNumber = strlen($characters);
        $codeLength = 10;

        $code = '';

        while (strlen($code) < 10) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }


        return $code;

    }

    public function collection(Collection $collection){

//       dd($collection);
        for ($i = 0; $i < count($collection); $i++) {

            User::query()
            ->updateOrCreate([
                'code' => $collection[$i]['code'],
            ],[
                'name' => $collection[$i]['name'],
                'password' => Hash::make('123456'),
                'birth_date' => Carbon::parse($collection[$i]['birth_date'])->format('Y-m-d'),
                'phone' => $collection[$i]['phone'],
                'father_phone' => $collection[$i]['father_phone'],
                'center' => $collection[$i]['center'],
                'user_status' => $collection[$i]['user_status'],
                'code' => $collection[$i]['code'] ?? $this->generateUniqueCode(),
                'subscription_months_groups' => $collection[$i]['subscription_months_groups'],
                'season_id' => $collection[$i]['season_id'],
                'country_id' => $collection[$i]['country_id'],
            ]);
        }
    }

//
//    public function map($row): array
//    {
//
//        if(gettype($row['birth_date']) == 'integer'){
//
//            $row['birth_date'] = Date::excelToDateTimeObject($row['birth_date'])->format('Y-m-d');
//            $row['date_start_code'] = Date::excelToDateTimeObject($row['date_start_code'])->format('Y-m-d');
//            $row['date_end_code'] = Date::excelToDateTimeObject($row['date_end_code'])->format('Y-m-d');
//        }
//
//        return $row;
//    }
}
