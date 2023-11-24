<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Season;
use App\Models\Term;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        for ($i=0;$i<= 10;$i++){

            Notification::create([

                'title' => 'اشعار جديد',
                'body' => 'يرجي متابعه الاجزاء القادمه من جزء الليزر',
                'season_id' => Season::first()->id,
                'term_id' => Term::first()->id,
            ]);
        }

    }
}
