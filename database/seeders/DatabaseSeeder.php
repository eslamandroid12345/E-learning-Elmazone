<?php

namespace Database\Seeders;

use App\Models\AllExam;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(10)->create();
        $this->call(AdminSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FullExamSeeder::class);
        $this->call(ExamInstructionSeeder::class);
        $this->call(ExamTimeSeeder::class);

    }
}
