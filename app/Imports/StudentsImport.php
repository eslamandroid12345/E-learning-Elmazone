<?php

namespace App\Imports;

use App\Models\Payment;
use App\Models\Subscribe;
use App\Models\User;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

            $user = User::query()
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



            $user = User::findOrFail($user->id);
            $months  = json_decode($user->subscription_months_groups,true);
            $subscriptions = Subscribe::query()
                ->whereHas('term', function (Builder $builder) use ($user){
                    $builder->where('status', '=', 'active')
                        ->where('season_id', '=',$user->season_id);
                })
                ->where('season_id', '=',$user->season_id);

            if($user->subscription_months_groups != null && $subscriptions->count() > 0){

                if($user->center == 'in'){

                    $allMonths = $subscriptions
                        ->pluck('month')
                        ->toArray();

                    $data = $subscriptions
                        ->pluck('price_in_center','month')
                        ->toArray();
                }else{
                    $allMonths = $subscriptions
                        ->pluck('month')
                        ->toArray();

                    $data = $subscriptions
                        ->pluck('price_out_center','month')
                        ->toArray();
                }

                $totalPricePaid = [];
                foreach ($months as $month){
                    if(in_array($month,$allMonths)){//1 [1,2,3,4,5]
                        UserSubscribe::query()
                            ->updateOrCreate([
                                'student_id' => $user->id,
                                'month' => $month
                            ],[
                                'student_id' => $user->id,
                                'month' => $month,
                                'year' => date('Y'),
                                'price' => $data[$month < 10 ? str_replace("0","",$month) : $month]
                            ]);

                        $totalPricePaid[] =  $data[$month < 10 ? str_replace("0","",$month) : $month];
                    }
                }

                Payment::query()
                    ->updateOrCreate([
                        'user_id' => $user->id,
                        'payment_type' => 'cash'
                    ],[
                        'user_id' => $user->id,
                        'transaction_status' => 'finished',
                        'payment_type' => 'cash',
                        'total_price' => array_sum($totalPricePaid)
                    ]);

            }
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
