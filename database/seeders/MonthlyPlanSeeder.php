<?php

namespace Database\Seeders;
use App\Models\MonthlyPlan;
use Illuminate\Database\Seeder;

class MonthlyPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $begin = new \DateTime('now');
        $end   = new \DateTime('2023-07-01');

        $different = $begin->diff($end);
        $days = $different->format('%a');//now do whatever you like with $days

        for($i=$begin; $i<=$end ;$i->modify('+1 day')) {
            $plan = new MonthlyPlan();
            $plan->background_color = "#FFEAD7";
            $plan->title_ar = "مراجعه الفصل الاول";
            $plan->title_en = "Review the first chapter";
            $plan->description_ar = "يجب مشاهده الدرس الاول والدرس التاني";
            $plan->description_en = "you must watch lesson one and lesson two";
            $plan->start = $i->format("Y-m-d");
            $plan->end = $i->modify('+1 day')->format("Y-m-d");
            $plan->season_id = 1;
            $plan->term_id = 1;
            $plan->save();
            if ($days > 1) {
                $i->modify('-1 day')->format("Y-m-d");
            }

        }
    }
}
