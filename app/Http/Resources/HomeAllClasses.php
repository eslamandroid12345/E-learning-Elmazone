<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\OnlineExam;
use App\Models\OpenLesson;
use App\Models\VideoOpened;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class HomeAllClasses extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'status' => $this->getSubjectClassStatus(),
            'title' => $this->getTitle(),
            'num_of_lessons' => $this->getLessonsCount(),
            'num_of_exams' => $this->getNumOfExams(),
        ];

    }

    private function getSubjectClassStatus(): string
    {
        $subjectClass = OpenLesson::query()
            ->where('user_id', '=',userId())
            ->where('subject_class_id', '=', $this->id)
            ->first();

        return $subjectClass ? 'opened' : 'lock';
    }

    private function getTitle(): string
    {
        return lang() == 'ar' ? $this->title_ar : $this->title_en;
    }

    private function getNumOfExams(): int
    {
        // Assuming $numOfExams is calculated somewhere in your code
        return OnlineExam::query()
            ->where('class_id','=',$this->id)
            ->count();
    }


    private function getLessonsCount(): int
    {
        // Assuming $numOfExams is calculated somewhere in your code
        return Lesson::query()
            ->select('id','subject_class_id')
            ->where('subject_class_id','=',$this->id)
            ->count();
    }
}
