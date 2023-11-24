<?php

namespace Database\Seeders;

use App\Models\VideoBasic;
use App\Models\VideoResource;
use Illuminate\Database\Seeder;

class VideoResourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0;$i<5;$i++){
            VideoResource::create([
                'name_ar' => 'مراجعه شامله علي الفصل الاول',
                'name_en' => 'Comprehensive review on the first chapter',
                'time' => 10,
                'video_link' => 'v'.$i.'mp4',
                'season_id' => 1,
                'term_id' => 1,
            ]);
        }
    }
}
