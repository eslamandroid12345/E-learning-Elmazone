<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1;$i<=12;$i++){

            Section::create([

                'section_name_ar' => $i . 'قاعه رقم ',
                'section_name_en' => 'Section number ' . $i,
                'address' => 'Address ' . $i,
                'capacity' => 2,
            ]);
        }

    }
}
