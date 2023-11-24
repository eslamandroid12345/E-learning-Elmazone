<?php

namespace Database\Seeders;

use App\Models\VideoBasic;
use Illuminate\Database\Seeder;

class VideoBasicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i=0;$i<5;$i++){
            VideoBasic::create([
                'name_ar' => 'اساسيات الفيزياء',
                'name_en' => 'Physics Basic',
                'time' => 10,
                'video_link' => 'v'.$i.'mp4',
            ]);
        }
    }
}
